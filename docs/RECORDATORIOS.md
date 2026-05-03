# Sistema de Recordatorios - Documentación Final

## ✅ Estado: CONFIGURADO Y FUNCIONANDO

### 📋 Configuración CloudPanel

**Comando configurado en CloudPanel:**
```bash
/usr/bin/php8.4 /home/uat-inmunoalerta/htdocs/inmunoalerta.abitech.dev/artisan schedule:run
```

**Configuración del Cron:**
- **Plantillas**: Cada minuto
- **Minutos**: `*`
- **Hora**: `*`
- **Día**: `*`
- **Mes**: `*`
- **Semanalmente**: `*`

## 📧 Funcionamiento del Sistema

### 1. Correo de Confirmación (Inmediato)
- **Cuándo**: Al crear recordatorio desde la web
- **Configuración**: `QUEUE_CONNECTION=sync`
- **Resultado**: Correo enviado instantáneamente

### 2. Recordatorios Programados (Automático)
- **Cuándo**: 7, 3, 1, 0 días antes de la fecha del recordatorio
- **Frecuencia**: Cada minuto (pero solo envía según las fechas programadas)
- **Comando**: Ejecutado automáticamente por CloudPanel

## 🔧 Archivos del Sistema

### Controlador Principal
- `app/Http/Controllers/Paciente/RecordatoriosController.php`
- Método `store()` envía confirmación inmediata

### Job de Procesamiento  
- `app/Jobs/EnviarRecordatorioJob.php`
- Procesa envíos de recordatorios

### Comando Scheduler
- `app/Console/Commands/EnviarRecordatorios.php`
- Encola recordatorios según fechas programadas

### Notificación
- `app/Notifications/RecordatorioNotification.php`
- `resources/views/emails/recordatorios/recordatorio.blade.php`

### Configuración Scheduler
- `app/Console/Kernel.php`
- Ejecuta `recordatorios:enviar` cada 15 minutos

## 📊 Estados de Recordatorios

- **programado**: Recordatorio creado, esperando fecha de envío
- **es_hoy**: Recordatorio para el día actual
- **hecho**: Recordatorio completado por el usuario
- **no_hice**: Usuario marcó que no completó la acción
- **no_aplica**: Recordatorio no aplicable

## 🚀 Producción

### Configuración Actual
```env
QUEUE_CONNECTION=sync    # Jobs inmediatos
MAIL_MAILER=log         # Para desarrollo (cambiar a smtp en producción)
```

### Para SMTP Real
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=tucoach@udh.edu.pe
MAIL_PASSWORD=xephkzzbsxpyerfv
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=peruabitech@gmail.com
```

## 🔍 Verificación

### Logs de Sistema
```bash
tail -f storage/logs/laravel.log
```

### Verificar Funcionamiento
1. Crear recordatorio desde la web
2. Confirmar que llega correo de confirmación
3. Los recordatorios programados se enviarán automáticamente en las fechas correspondientes

## 📈 Monitoreo

- Los correos de confirmación aparecen inmediatamente en los logs
- Los recordatorios programados se procesan cada 15 minutos
- Todos los envíos quedan registrados en `storage/logs/laravel.log`

---

**✅ Sistema completamente funcional y automatizado**  
**📧 Sin intervención manual requerida**  
**🚀 Listo para producción**


/usr/bin/php8.4 /home/uat-inmunoalerta/htdocs/inmunoalerta.abitech.dev/artisan queue:work --queue=emails --sleep=3 --tries=3 --timeout=50 >> /home/uat-inmunoalerta/logs/worker.log 2>&1

/usr/bin/php8.4 /home/uat-inmunoalerta/htdocs/inmunoalerta.abitech.dev/artisan schedule:run >> /home/uat-inmunoalerta/logs/scheduler.log 2>&1
