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
        if (!$recordatorio) {
            Log::warning('Recordatorio no encontrado con ID: ' . $this->recordatorioId);
            return;
        }

        // Para el stage 'created', enviamos sin importar el estado
        if ($this->stage !== 'created') {
            // Only send if still pending or programado for other stages
            if (!in_array($recordatorio->estado, ['programado', 'es_hoy'])) {
                Log::info('Recordatorio ID ' . $this->recordatorioId . ' no está en estado válido para envío: ' . $recordatorio->estado);
                return;
            }
        }

        try {
            if (!$recordatorio->usuario) {
                Log::error('Usuario no encontrado para recordatorio ID: ' . $this->recordatorioId);
                return;
            }

            Log::info('Enviando notificación para recordatorio ID: ' . $this->recordatorioId . ' stage: ' . $this->stage);
            $recordatorio->usuario->notify(new RecordatorioNotification($recordatorio, $this->stage));

            // Solo marcar como enviado si no es el stage 'created'
            if ($this->stage !== 'created') {
                $recordatorio->marcarComoEnviado();
            }

            Log::info('Notificación enviada exitosamente para recordatorio ID: ' . $this->recordatorioId);

        } catch (\Exception $e) {
            Log::error('Error enviando recordatorio id ' . $this->recordatorioId . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            // Let the job fail and be retried according to queue config
            throw $e;
        }
    }
}
