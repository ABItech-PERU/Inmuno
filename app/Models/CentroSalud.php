<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroSalud extends Model
{
    use HasFactory;

    protected $table = 'centros_salud';

    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'nivel_atencion',
        'direccion',
        'telefono',
        'email',
        'distrito_codigo',
        'latitud',
        'longitud',
        'capacidad',
        'horario_inicio',
        'horario_fin',
        'servicios',
        'director_medico',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'capacidad' => 'integer',
        'servicios' => 'array',
        'horario_inicio' => 'datetime:H:i',
        'horario_fin' => 'datetime:H:i',
    ];

    /**
     * Relación con distrito
     */
    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class, 'distrito_codigo', 'codigo_ubigeo');
    }

    /**
     * Relación con provincia a través de distrito
     */
    public function provincia()
    {
        return $this->hasOneThrough(
            Provincia::class,
            Distrito::class,
            'codigo_ubigeo', // Foreign key en distrito
            'codigo_ubigeo', // Foreign key en provincia
            'distrito_codigo', // Local key en centro_salud
            'provincia_codigo' // Local key en distrito
        );
    }

    /**
     * Relación con departamento a través de distrito y provincia
     */
    public function departamento()
    {
        return $this->distrito->provincia->departamento ?? null;
    }

    /**
     * Relación con aplicaciones de vacunas
     */
    public function aplicaciones(): HasMany
    {
        return $this->hasMany(AplicacionVacuna::class);
    }

    /**
     * Relación con citas
     */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Scope para centros activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar por nivel
     */
    public function scopePorNivel($query, $nivel)
    {
        return $query->where('nivel', $nivel);
    }

    /**
     * Accessor para obtener el nombre completo con ubicación
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' - ' . ($this->distrito->nombre ?? '');
    }

    /**
     * Método auxiliar para calcular distancia
     */
    public function getDistanciaAttribute()
    {
        // Aquí se podría calcular la distancia basada en las coordenadas del usuario
        return null;
    }
}
