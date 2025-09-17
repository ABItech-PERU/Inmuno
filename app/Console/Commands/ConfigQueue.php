<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConfigQueue extends Command
{
    protected $signature = 'config:queue {mode=sync : sync|database}';
    protected $description = 'Cambiar configuración de cola entre sync (inmediato) y database (background)';

    public function handle()
    {
        $mode = $this->argument('mode');

        if (!in_array($mode, ['sync', 'database'])) {
            $this->error('Modo debe ser "sync" o "database"');
            return 1;
        }

        // Leer el .env actual
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        // Cambiar QUEUE_CONNECTION
        if (str_contains($envContent, 'QUEUE_CONNECTION=')) {
            $envContent = preg_replace('/QUEUE_CONNECTION=.*/', "QUEUE_CONNECTION={$mode}", $envContent);
        } else {
            $envContent .= "\nQUEUE_CONNECTION={$mode}";
        }

        // Guardar cambios
        file_put_contents($envFile, $envContent);

        // Limpiar configuración cache
        $this->call('config:clear');

        $this->info("✅ Configuración de cola cambiada a: {$mode}");

        if ($mode === 'sync') {
            $this->info("⚡ Los jobs se ejecutarán inmediatamente (sin cola)");
            $this->info("🎯 Perfecto para desarrollo - no necesitas worker");
        } else {
            $this->info("📋 Los jobs se encolarán en base de datos");
            $this->warn("⚠️  Necesitas ejecutar: php artisan queue:work --queue=emails");
        }

        return 0;
    }
}
