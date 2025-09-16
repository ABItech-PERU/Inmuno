<?php

namespace App\Console\Commands;

use App\Jobs\EnviarRecordatorioJob;
use App\Models\Recordatorio;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnviarRecordatorios extends Command
{
    protected $signature = 'recordatorios:enviar';
    protected $description = 'Enviar emails de recordatorios programados (7,3,1,0 días antes)';

    public function handle()
    {
        $this->info('Buscando recordatorios pendientes para enviar...');

        $hoy = Carbon::today();

        $offsets = [7,3,1,0];

        foreach ($offsets as $offset) {
            $fechaObjetivo = $hoy->copy()->addDays($offset)->toDateString();

            $recordatorios = Recordatorio::where('estado','programado')
                ->whereDate('fecha_recordatorio', $fechaObjetivo)
                ->get();

            foreach ($recordatorios as $r) {
                EnviarRecordatorioJob::dispatch($r->id, $offset)->onQueue('emails');
                $this->line("Enqueued recordatorio {$r->id} para fecha {$fechaObjetivo} (offset {$offset})");
            }
        }

        $this->info('Proceso completado.');

        return 0;
    }
}
