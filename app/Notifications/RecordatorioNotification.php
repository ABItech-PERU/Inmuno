<?php

namespace App\Notifications;

use App\Models\Recordatorio;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class RecordatorioNotification extends Notification
{
    use Queueable;

    protected $recordatorio;
    protected $stage; // e.g. 'created', 7,3,1,0

    public function __construct(Recordatorio $recordatorio, $stage = null)
    {
        $this->recordatorio = $recordatorio;
        $this->stage = $stage;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Usar vista blade para email
        $r = $this->recordatorio;

        $subject = "Recordatorio: " . ($r->titulo ?? 'Recordatorio');
        if ($this->stage && is_numeric($this->stage)) {
            $subject = "🔔 Recordatorio en {$this->stage} día" . ($this->stage > 1 ? 's' : '') . ": {$r->titulo}";
        } elseif ($this->stage === 'created') {
            $subject = "✅ ¡Recordatorio creado! {$r->titulo}";
        } elseif ($this->stage === 'reminder') {
            $tipo = $r->vacuna ? 'vacuna' : 'cita';
            $subject = "💉 ¡Recuerda tu {$tipo}! {$r->titulo}";
        }

        $url = URL::to('/paciente/recordatorios/' . $r->id);

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.recordatorios.recordatorio', [
                'recordatorio' => $r,
                'stage' => $this->stage,
                'url' => $url,
                'notifiable' => $notifiable,
            ]);
    }
}
