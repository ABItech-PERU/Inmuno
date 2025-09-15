<?php

namespace App\Console\Commands;

use App\Models\GrupoEdad;
use Illuminate\Console\Command;

class GestionarGruposEdad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grupos-edad:gestionar {accion} {grupo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestionar grupos de edad (activar/desactivar). Uso: grupos-edad:gestionar {activar|desactivar|listar} {ID-del-grupo}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accion = $this->argument('accion');
        $grupoId = $this->argument('grupo');

        switch ($accion) {
            case 'listar':
                return $this->listarGrupos();

            case 'activar':
                if (!$grupoId) {
                    $this->error('❌ Debes especificar el ID del grupo a activar');
                    return Command::FAILURE;
                }
                return $this->cambiarEstado($grupoId, true);

            case 'desactivar':
                if (!$grupoId) {
                    $this->error('❌ Debes especificar el ID del grupo a desactivar');
                    return Command::FAILURE;
                }
                return $this->cambiarEstado($grupoId, false);

            default:
                $this->error("❌ Acción no válida: {$accion}");
                $this->info("💡 Acciones disponibles: listar, activar, desactivar");
                $this->info("   Ejemplo: php artisan grupos-edad:gestionar listar");
                $this->info("   Ejemplo: php artisan grupos-edad:gestionar activar 1");
                return Command::FAILURE;
        }
    }

    /**
     * Listar todos los grupos
     */
    private function listarGrupos()
    {
        $grupos = GrupoEdad::ordenado()->get();

        $this->info('📋 Lista de Grupos de Edad');
        $this->info('==========================');

        if ($grupos->count() > 0) {
            $headers = ['ID', 'Orden', 'Nombre', 'Estado', 'Rango de Edad'];
            $rows = [];

            foreach ($grupos as $grupo) {
                $estado = $grupo->activo ? '<info>Activo</info>' : '<comment>Inactivo</comment>';
                $rango = $this->formatRangoEdad($grupo->edad_inicio_meses, $grupo->edad_fin_meses);

                $rows[] = [
                    $grupo->id,
                    $grupo->orden,
                    $grupo->nombre,
                    $estado,
                    $rango
                ];
            }

            $this->table($headers, $rows);
        } else {
            $this->error('❌ No se encontraron grupos de edad');
        }

        return Command::SUCCESS;
    }

    /**
     * Cambiar estado de un grupo
     */
    private function cambiarEstado($grupoId, $activo)
    {
        $grupo = GrupoEdad::find($grupoId);

        if (!$grupo) {
            $this->error("❌ No se encontró el grupo con ID: {$grupoId}");
            return Command::FAILURE;
        }

        $estadoAnterior = $grupo->activo ? 'activo' : 'inactivo';
        $nuevoEstado = $activo ? 'activo' : 'inactivo';

        if ($grupo->activo === $activo) {
            $this->warn("⚠️  El grupo '{$grupo->nombre}' ya está {$nuevoEstado}");
            return Command::SUCCESS;
        }

        $grupo->activo = $activo;
        $grupo->save();

        $accion = $activo ? 'activado' : 'desactivado';
        $emoji = $activo ? '✅' : '❌';

        $this->info("{$emoji} Grupo '{$grupo->nombre}' {$accion} exitosamente");
        $this->info("   Estado anterior: {$estadoAnterior} → {$nuevoEstado}");

        return Command::SUCCESS;
    }

    /**
     * Formatear rango de edad
     */
    private function formatRangoEdad($edadInicio, $edadFin)
    {
        if ($edadInicio === null && $edadFin === null) {
            return 'Sin límite';
        } elseif ($edadInicio !== null && $edadFin === null) {
            return 'Desde ' . $this->formatEdad($edadInicio);
        } elseif ($edadInicio === null && $edadFin !== null) {
            return 'Hasta ' . $this->formatEdad($edadFin);
        } elseif ($edadInicio === $edadFin) {
            return $this->formatEdad($edadInicio);
        } else {
            return $this->formatEdad($edadInicio) . ' - ' . $this->formatEdad($edadFin);
        }
    }

    /**
     * Formatear edad de meses a texto legible
     */
    private function formatEdad($meses)
    {
        if ($meses === 0) return '0m';
        if ($meses < 12) return "{$meses}m";

        $años = floor($meses / 12);
        $mesesRestantes = $meses % 12;

        if ($mesesRestantes === 0) {
            return "{$años}a";
        } else {
            return "{$años}a {$mesesRestantes}m";
        }
    }
}
