<?php

namespace App\Jobs;

use App\Models\Recordatorio;
use App\Notifications\RecordatorioNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarRecordatorioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recordatorioId;
    public $stage;

    public function __construct(int $recordatorioId, $stage = null)
    {
        $this->recordatorioId = $recordatorioId;
        $this->stage = $stage;
    }

    public function handle()
    {
        $recordatorio = Recordatorio::with(['usuario','dependiente'])->find($this->recordatorioId);
        if (!$recordatorio) return;

        // Only send if still pending or programado
        if (!in_array($recordatorio->estado, ['programado', 'es_hoy'])) {
            return;
        }

        try {
            $recordatorio->usuario->notify(new RecordatorioNotification($recordatorio, $this->stage));
            $recordatorio->marcarComoEnviado();
        } catch (\Exception $e) {
            Log::error('Error enviando recordatorio id ' . $this->recordatorioId . ': ' . $e->getMessage());
            // Let the job fail and be retried according to queue config
            throw $e;
        }
    }
}
