<?php

namespace App\Console\Commands;

use App\Models\Vacuna;
use Illuminate\Console\Command;

class LimpiarVacunasDuplicadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vacunas:limpiar-duplicados {--verificar : Solo verificar duplicados sin eliminar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar y limpiar vacunas duplicadas en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando vacunas duplicadas...');

        // Encontrar nombres duplicados
        $duplicados = Vacuna::select('nombre')
            ->groupBy('nombre')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nombre');

        if ($duplicados->isEmpty()) {
            $this->info('✅ No se encontraron vacunas duplicadas.');
            return 0;
        }

        $this->warn("⚠️  Se encontraron {$duplicados->count()} nombres de vacunas duplicados:");

        $totalDuplicados = 0;
        $detalles = [];

        foreach ($duplicados as $nombre) {
            $vacunas = Vacuna::where('nombre', $nombre)->orderBy('id')->get();
            $totalDuplicados += $vacunas->count() - 1; // -1 porque uno se mantiene

            $this->line("\n📋 Vacuna: {$nombre}");

            foreach ($vacunas as $index => $vacuna) {
                $status = $index === 0 ? '✅ MANTENER' : '❌ ELIMINAR';
                $activa = $vacuna->activa ? 'Activa' : 'Inactiva';
                $this->line("   ID: {$vacuna->id} | {$activa} | Creada: {$vacuna->created_at} | {$status}");
            }

            $detalles[$nombre] = $vacunas;
        }

        if ($this->option('verificar')) {
            $this->info("\n🔍 Verificación completada. Total de registros duplicados a eliminar: {$totalDuplicados}");
            $this->line('💡 Ejecuta el comando sin --verificar para limpiar los duplicados.');
            return 0;
        }

        // Confirmar antes de eliminar
        if (!$this->confirm("\n¿Deseas eliminar {$totalDuplicados} registros duplicados? (Se mantendrá el registro más antiguo de cada vacuna)")) {
            $this->info('❌ Operación cancelada.');
            return 0;
        }

        $eliminados = 0;

        foreach ($detalles as $nombre => $vacunas) {
            // Mantener el primero (más antiguo) y eliminar los demás
            $mantener = $vacunas->first();
            $aEliminar = $vacunas->skip(1);

            foreach ($aEliminar as $vacuna) {
                // Verificar si tiene aplicaciones antes de eliminar
                if ($vacuna->aplicaciones()->exists()) {
                    $this->warn("⚠️  No se puede eliminar ID {$vacuna->id} ({$nombre}) porque tiene aplicaciones registradas.");
                    continue;
                }

                $vacuna->delete();
                $eliminados++;
                $this->line("🗑️  Eliminado: ID {$vacuna->id} ({$nombre})");
            }
        }

        $this->info("\n✅ Proceso completado. Se eliminaron {$eliminados} registros duplicados.");

        if ($eliminados < $totalDuplicados) {
            $noEliminados = $totalDuplicados - $eliminados;
            $this->warn("⚠️  {$noEliminados} registros no se pudieron eliminar porque tienen aplicaciones asociadas.");
        }

        return 0;
    }
}
