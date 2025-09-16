Recordatorios - Envío de correos

Qué se agregó
- `App\Notifications\RecordatorioNotification` - notificación por email con detalles del recordatorio.
- `App\Jobs\EnviarRecordatorioJob` - job queued que envía la notificación y marca como enviado.
- `App\Console\Commands\EnviarRecordatorios` - comando para encolar envíos para offsets 7,3,1,0 días.

Cómo programar el envío
- Si tienes `app/Console/Kernel.php` agrega en `schedule()`:

  Agrega dentro del método `schedule(Schedule $schedule)`:

      $schedule->command('recordatorios:enviar')->everyFifteenMinutes()->withoutOverlapping();

  Si tu proyecto no tiene `app/Console/Kernel.php` visible (por ejemplo en entornos especiales), en Windows puedes crear una tarea programada que ejecute el comando directamente cada 15 minutos:

  - Acción: Ejecutar `php`
  - Argumentos: `C:\laragon\www\inmuno\artisan recordatorios:enviar`
  - Inicio en: `C:\laragon\www\inmuno`


- En producción ejecuta `php artisan schedule:run` cada minuto vía cron o configura una tarea programada en Windows.

Colas y MAIL
- Configura `.env` con `QUEUE_CONNECTION=database` y corre `php artisan queue:work` o usa `sync` para envíos síncronos.
- Configura `MAIL_` (Mailtrap, SMTP) en `.env`.

Pruebas
- Se añadió un test básico que finge notificaciones y crea un recordatorio para comprobar el envío de confirmación.
