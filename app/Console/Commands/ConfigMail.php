<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConfigMail extends Command
{
    protected $signature = 'config:mail {mode=log : log|smtp}';
    protected $description = 'Cambiar configuración de correo entre log y smtp';

    public function handle()
    {
        $mode = $this->argument('mode');

        if (!in_array($mode, ['log', 'smtp'])) {
            $this->error('Modo debe ser "log" o "smtp"');
            return 1;
        }

        // Leer el .env actual
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        // Cambiar MAIL_MAILER
        if (str_contains($envContent, 'MAIL_MAILER=')) {
            $envContent = preg_replace('/MAIL_MAILER=.*/', "MAIL_MAILER={$mode}", $envContent);
        } else {
            $envContent .= "\nMAIL_MAILER={$mode}";
        }

        // Guardar cambios
        file_put_contents($envFile, $envContent);

        // Limpiar configuración cache
        $this->call('config:clear');

        $this->info("✅ Configuración de correo cambiada a: {$mode}");

        if ($mode === 'log') {
            $this->info("📝 Los correos se guardarán en: storage/logs/laravel.log");
        } else {
            $this->info("📧 Los correos se enviarán vía SMTP");
            $this->warn("⚠️  Asegúrate de que la configuración SMTP sea correcta");
        }

        return 0;
    }
}
