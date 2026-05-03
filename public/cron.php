<?php
// Archivo: public/cron.php
// Seguridad: verificar token desde CLI o HTTP
$token = $_GET['token'] ?? $argv[1] ?? '';

if ($token !== 'inmuno-cron-2025') {
    if (php_sapi_name() === 'cli') {
        echo "❌ Token requerido. Uso: php cron.php inmuno-cron-2025\n";
        exit(1);
    } else {
        http_response_code(403);
        die('Acceso denegado');
    }
}

// Cargar Laravel
require_once '../vendor/autoload.php';

try {
    $app = require_once '../bootstrap/app.php';

    // Ejecutar el scheduler
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->call('schedule:run');

    // Respuesta exitosa
    $message = "✅ Scheduler ejecutado correctamente: " . date('Y-m-d H:i:s') . "\n📧 Recordatorios procesados\n";

    if (php_sapi_name() === 'cli') {
        echo $message;
    } else {
        echo nl2br($message);
    }

} catch (Exception $e) {
    $error = "❌ Error: " . $e->getMessage() . "\n";

    if (php_sapi_name() === 'cli') {
        echo $error;
        exit(1);
    } else {
        http_response_code(500);
        echo $error;
    }

    // Log del error
    error_log("Cron Error: " . $e->getMessage());
}
?>
