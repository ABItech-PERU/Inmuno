<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Recordatorio;
use App\Notifications\RecordatorioNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailQuick extends Command
{
    protected $signature = 'test:mail-quick {email?}';
    protected $description = 'Probar el envío de correos con timeout corto';

    public function handle()
    {
        $email = $this->argument('email') ?? '29abimael@gmail.com';

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Usuario con email {$email} no encontrado");
            return;
        }

        $recordatorio = Recordatorio::first();
        if (!$recordatorio) {
            $this->error('No hay recordatorios en la base de datos');
            return;
        }

        $this->info("🚀 Enviando correo de prueba a: {$email}");
        $this->info("📧 Configuración SMTP:");
        $this->info("   - Host: " . config('mail.mailers.smtp.host'));
        $this->info("   - Port: " . config('mail.mailers.smtp.port'));
        $this->info("   - Username: " . config('mail.mailers.smtp.username'));
        $this->info("   - From: " . config('mail.from.address'));

        // Configurar timeout más corto
        config(['mail.mailers.smtp.timeout' => 10]);

        $startTime = time();

        try {
            $user->notify(new RecordatorioNotification($recordatorio, 'created'));
            $duration = time() - $startTime;
            $this->info("✅ Correo enviado exitosamente en {$duration} segundos!");
            $this->info("📬 Revisa tu bandeja de entrada en {$email}");
        } catch (\Exception $e) {
            $duration = time() - $startTime;
            $this->error("❌ Error después de {$duration} segundos: " . $e->getMessage());

            if (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'Connection')) {
                $this->warn("💡 Sugerencia: Verifica la configuración de red o firewall");
                $this->warn("💡 También puede ser necesario usar una 'App Password' para Gmail");
            }
        }
    }
}
