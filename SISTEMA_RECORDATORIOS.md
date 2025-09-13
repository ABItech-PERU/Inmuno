# Sistema de Recordatorios de Vacunación - Inmuno Alerta

## Descripción General

Este módulo permite a los pacientes gestionar sus recordatorios de vacunación y los de sus dependientes (hijos, apoderados, etc.). El sistema incluye:

- Gestión de dependientes (registro de hijos/apoderados)
- Creación y gestión de recordatorios personalizados
- Generación automática de recordatorios basados en esquemas de vacunación
- Seguimiento del estado de recordatorios
- Integración con el sistema de usuarios y roles

## Funcionalidades Principales

### 1. Gestión de Dependientes
- **Registro de dependientes**: Los pacientes pueden registrar a sus hijos o personas a cargo
- **Vinculación automática**: Si un dependiente se registra posteriormente en el sistema, se vincula automáticamente
- **Datos básicos**: Nombres, apellidos, documento, fecha de nacimiento, género, parentesco
- **Edición y desactivación**: Los dependientes pueden ser editados o desactivados

### 2. Sistema de Recordatorios
- **Recordatorios propios**: Para el paciente titular
- **Recordatorios de dependientes**: Para personas a cargo del paciente
- **Tipos de recordatorios**:
  - Vacuna próxima
  - Cita programada
  - Refuerzo pendiente
  - Recordatorio personal
- **Estados**: Pendiente, Enviado, Leído
- **Filtros**: Por estado, tipo, fecha

### 3. Generación Automática
- **Basada en esquemas**: Utiliza los esquemas de vacunación del MINSA
- **Inteligente**: Evita duplicados y verifica vacunas ya aplicadas
- **Personalizada**: Considera la edad y el historial de cada persona
- **Programable**: Se puede ejecutar automáticamente mediante comandos

## Estructura Técnica

### Modelos
- **Dependiente**: Gestiona las relaciones tutor-dependiente
- **Recordatorio**: Maneja los recordatorios de vacunación
- **User**: Extendido con relaciones para dependientes
- **EsquemaVacunacion**: Define los esquemas de vacunación
- **DosisVacuna**: Define las dosis específicas por esquema

### Controlador
- **RecordatoriosController**: Gestiona todas las operaciones CRUD
- **Métodos principales**:
  - `index()`: Lista recordatorios con filtros
  - `store()`: Crea nuevos recordatorios
  - `dependientes()`: Gestiona dependientes
  - `storeDependiente()`: Registra nuevos dependientes

### Rutas
```php
// Módulo de Recordatorios
Route::get('/recordatorios', 'index')->name('recordatorios.index');
Route::post('/recordatorios', 'store')->name('recordatorios.store');
Route::put('/recordatorios/{recordatorio}', 'update')->name('recordatorios.update');
Route::delete('/recordatorios/{recordatorio}', 'destroy')->name('recordatorios.destroy');
Route::patch('/recordatorios/{recordatorio}/leido', 'marcarComoLeido')->name('recordatorios.marcar-leido');

// Gestión de Dependientes
Route::get('/dependientes', 'dependientes')->name('dependientes.index');
Route::post('/dependientes', 'storeDependiente')->name('dependientes.store');
Route::put('/dependientes/{dependiente}', 'updateDependiente')->name('dependientes.update');
Route::delete('/dependientes/{dependiente}', 'destroyDependiente')->name('dependientes.destroy');
```

### Vistas
- **Index.vue**: Vista principal con lista de recordatorios, estadísticas y filtros
- **Dependientes.vue**: Gestión completa de dependientes con formularios

### Comando Artisan
```bash
php artisan recordatorios:generar [--user_id=] [--dependiente_id=]
```

## Casos de Uso

### 1. Madre con bebé recién nacido
1. La madre se registra como paciente
2. Registra a su bebé como dependiente
3. El sistema genera automáticamente recordatorios para BCG, Hepatitis B, etc.
4. Recibe notificaciones cuando corresponde cada vacuna
5. Puede crear recordatorios personalizados adicionales

### 2. Padre con hijo adolescente
1. El padre registra a su hijo adolescente
2. Si el hijo después se registra con su DNI, el sistema los vincula automáticamente
3. El hijo puede ver su historial de vacunación desde pequeño
4. Ambos pueden gestionar los recordatorios

### 3. Tutor legal
1. Registra a sus pupilos/apoderados
2. Gestiona los recordatorios de vacunación de cada uno
3. Mantiene control centralizado de la información de salud

## Características Técnicas

### Seguridad
- Middleware de autenticación y autorización
- Validación de datos en formularios
- Protección contra acceso no autorizado a datos de otros usuarios

### Escalabilidad
- Paginación en listas
- Carga lazy de relaciones
- Índices en base de datos para consultas eficientes

### Usabilidad
- Interfaz responsiva
- Filtros dinámicos
- Modales para formularios
- Feedback visual del estado de recordatorios

### Integración
- Compatible con el sistema de roles existente
- Integración con esquemas de vacunación del MINSA
- Extensible para futuras funcionalidades (notificaciones por email/SMS)

## Instalación y Configuración

### 1. Ejecutar migraciones
```bash
php artisan migrate
```

### 2. Poblar datos básicos
```bash
php artisan db:seed --class=EsquemasVacunacionSeeder
```

### 3. Generar recordatorios iniciales
```bash
php artisan recordatorios:generar
```

### 4. Programar generación automática (opcional)
Agregar al cron del servidor:
```bash
0 6 * * * cd /ruta/al/proyecto && php artisan recordatorios:generar
```

## Futuras Mejoras

1. **Notificaciones**:
   - Email automático
   - SMS
   - Notificaciones push

2. **Reportes**:
   - Cobertura de vacunación
   - Estadísticas familiares
   - Exportación de datos

3. **Integración Externa**:
   - API del MINSA
   - Centros de salud
   - Calendarios externos

4. **Gamificación**:
   - Logros por cumplimiento
   - Recordatorios atractivos para niños
   - Sistema de recompensas

## Consideraciones Importantes

- El sistema respeta la privacidad de datos médicos
- Cumple con normativas de protección de datos personales
- Permite la portabilidad de información (cuando el dependiente se hace mayor)
- Mantiene trazabilidad de todas las acciones realizadas
