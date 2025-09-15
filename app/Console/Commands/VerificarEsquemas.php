<?php

namespace App\Console\Commands;

use App\Models\EsquemaVacunacion;
use Illuminate\Console\Command;

class VerificarEsquemas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esquemas:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar los esquemas de vacunación creados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $esquemas = EsquemaVacunacion::all();

        $this->info('📋 Esquemas de Vacunación en la Base de Datos');
        $this->info('=============================================');
        $this->info('Total de esquemas: ' . $esquemas->count());
        $this->newLine();

        if ($esquemas->count() > 0) {
            foreach ($esquemas as $esquema) {
                $estado = $esquema->activo ? '✅' : '❌';
                $tipo = $esquema->obligatorio ? '🔴 Obligatorio' : '🟡 Opcional';

                $this->line("$estado {$esquema->id}. {$esquema->nombre}");
                $this->line("   Grupo: {$esquema->grupo_edad} | $tipo");

                if ($esquema->edad_inicio !== null || $esquema->edad_fin !== null) {
                    $rangoEdad = $this->formatRangoEdad($esquema->edad_inicio, $esquema->edad_fin);
                    $this->line("   Rango edad: {$rangoEdad}");
                }
                $this->newLine();
            }
        } else {
            $this->error('❌ No se encontraron esquemas de vacunación.');
            $this->info('💡 Ejecuta: php artisan db:seed --class=EsquemasVacunacionSeeder');
        }

        return Command::SUCCESS;
    }

    /**
     * Formatear edad de meses a texto legible
     */
    private function formatEdad($meses)
    {
        if (!$meses && $meses !== 0) return 'No especificada';

        // Para recién nacidos
        if ($meses === 0) return 'Recién nacido';

        // Para meses menores a 12
        if ($meses < 12) return "$meses " . ($meses === 1 ? 'mes' : 'meses');

        // Para años completos
        $años = floor($meses / 12);
        $mesesRestantes = $meses % 12;

        if ($mesesRestantes === 0) {
            return "$años " . ($años === 1 ? 'año' : 'años');
        }

        // Para combinaciones de años y meses (solo hasta 2 años, después solo años)
        if ($años <= 2) {
            return "{$años}a {$mesesRestantes}m";
        } else {
            return "$años años";
        }
    }

    /**
     * Formatear rango de edad
     */
    private function formatRangoEdad($edadInicio, $edadFin)
    {
        if ($edadInicio === null && $edadFin === null) {
            return 'Sin límite de edad';
        } else if ($edadInicio !== null && $edadFin === null) {
            return 'Desde ' . $this->formatEdad($edadInicio);
        } else if ($edadInicio === null && $edadFin !== null) {
            return 'Hasta ' . $this->formatEdad($edadFin);
        } else if ($edadInicio === $edadFin) {
            // Si inicio y fin son iguales, mostrar solo uno
            return $this->formatEdad($edadInicio);
        } else {
            return $this->formatEdad($edadInicio) . ' a ' . $this->formatEdad($edadFin);
        }
    }
}
