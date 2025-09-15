<?php

namespace App\Console\Commands;

use App\Models\GrupoEdad;
use Illuminate\Console\Command;

class VerificarGruposEdad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grupos-edad:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar los grupos de edad disponibles en el sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $grupos = GrupoEdad::ordenado()->get();

        $this->info('📋 Grupos de Edad en el Sistema');
        $this->info('===============================');
        $this->info('Total de grupos: ' . $grupos->count());
        $this->newLine();

        if ($grupos->count() > 0) {
            foreach ($grupos as $grupo) {
                $estado = $grupo->activo ? '✅' : '❌';
                $rango = $this->formatRangoEdad($grupo->edad_inicio_meses, $grupo->edad_fin_meses);

                $this->line("$estado {$grupo->orden}. {$grupo->nombre}");
                $this->line("   Rango: {$rango}");
                if ($grupo->descripcion) {
                    $this->line("   Descripción: {$grupo->descripcion}");
                }
                $this->newLine();
            }

            // Estadísticas
            $activos = $grupos->where('activo', true)->count();
            $inactivos = $grupos->where('activo', false)->count();

            $this->info("📊 Resumen:");
            $this->info("   • Grupos activos: {$activos}");
            if ($inactivos > 0) {
                $this->warn("   • Grupos inactivos: {$inactivos}");
            }

        } else {
            $this->error('❌ No se encontraron grupos de edad.');
            $this->info('💡 Ejecuta: php artisan db:seed --class=GruposEdadSeeder');
        }

        return Command::SUCCESS;
    }

    /**
     * Formatear rango de edad
     */
    private function formatRangoEdad($edadInicio, $edadFin)
    {
        if ($edadInicio === null && $edadFin === null) {
            return 'Sin límite de edad';
        } elseif ($edadInicio !== null && $edadFin === null) {
            return 'Desde ' . $this->formatEdad($edadInicio);
        } elseif ($edadInicio === null && $edadFin !== null) {
            return 'Hasta ' . $this->formatEdad($edadFin);
        } elseif ($edadInicio === $edadFin) {
            return $this->formatEdad($edadInicio);
        } else {
            return $this->formatEdad($edadInicio) . ' a ' . $this->formatEdad($edadFin);
        }
    }

    /**
     * Formatear edad de meses a texto legible
     */
    private function formatEdad($meses)
    {
        if ($meses === 0) return 'Recién nacido';
        if ($meses < 12) return "$meses " . ($meses === 1 ? 'mes' : 'meses');

        $años = floor($meses / 12);
        $mesesRestantes = $meses % 12;

        if ($mesesRestantes === 0) {
            return "$años " . ($años === 1 ? 'año' : 'años');
        }

        if ($años <= 2) {
            return "{$años}a {$mesesRestantes}m";
        } else {
            return "$años años";
        }
    }
}
