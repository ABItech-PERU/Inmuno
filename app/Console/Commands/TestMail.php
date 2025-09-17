<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Recordatorio;
use App\Notifications\RecordatorioNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'test:mail {email?}';
    protected $description = 'Probar el envío de correos';

    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $user = User::first();
            if (!$user) {
                $this->error('No hay usuarios en la base de datos');
                return;
            }
            $email = $user->email;
        } else {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("Usuario con email {$email} no encontrado");
                return;
            }
        }

        $recordatorio = Recordatorio::first();
        if (!$recordatorio) {
            $this->error('No hay recordatorios en la base de datos');
            return;
        }

        $this->info("Enviando correo de prueba a: {$email}");
        $this->info("Configuración actual:");
        $this->info("- Mail Driver: " . config('mail.default'));
        $this->info("- SMTP Host: " . config('mail.mailers.smtp.host'));
        $this->info("- SMTP Port: " . config('mail.mailers.smtp.port'));

        try {
            $user->notify(new RecordatorioNotification($recordatorio, 'created'));
            $this->info("✅ Correo enviado exitosamente!");
        } catch (\Exception $e) {
            $this->error("❌ Error enviando correo: " . $e->getMessage());
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
        }
    }
}
