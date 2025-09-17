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
            Log::warning("Recordatorio #{$this->recordatorioId} no encontrado en la base de datos.");
            return;
        }

        // Para el stage 'created', enviamos sin importar el estado
        if ($this->stage !== 'created') {
            // Only send if still pending or programado for other stages
            if (!in_array($recordatorio->estado, ['programado', 'es_hoy'])) {
                Log::info("Recordatorio #{$this->recordatorioId} saltado: estado '{$recordatorio->estado}' no valido para envio.");
                return;
            }

            // No enviar si ya fue enviado
            if ($recordatorio->enviado_en) {
                Log::info("Recordatorio #{$this->recordatorioId} ya enviado previamente, omitiendo.");
                return;
            }
        }

        try {
            if (!$recordatorio->usuario) {
                Log::error("Usuario no encontrado para recordatorio #{$this->recordatorioId}.");
                return;
            }

            Log::info("Enviando notificacion de recordatorio #{$this->recordatorioId} (tipo: {$this->stage})");
            $recordatorio->usuario->notify(new RecordatorioNotification($recordatorio, $this->stage));

            // Solo marcar como enviado si no es el stage 'created'
            if ($this->stage !== 'created' && ($this->stage === 'reminder' || !$recordatorio->hora_recordatorio)) {
                $recordatorio->marcarComoEnviado();
            }

            Log::info("Notificacion enviada exitosamente para recordatorio #{$this->recordatorioId}");

        } catch (\Exception $e) {
            Log::error("Error enviando recordatorio #{$this->recordatorioId}: {$e->getMessage()}");
            Log::error('Stack trace: ' . $e->getTraceAsString());
            // Let the job fail and be retried according to queue config
            throw $e;
        }
    }
}
