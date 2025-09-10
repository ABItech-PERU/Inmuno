<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distrito extends Model
{
    protected $fillable = [
        'codigo_ubigeo',
        'nombre',
        'provincia_codigo',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Usar el código UBIGEO como clave primaria para las relaciones
    public function getRouteKeyName()
    {
        return 'codigo_ubigeo';
    }

    /**
     * Relación con provincia
     */
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincia_codigo', 'codigo_ubigeo');
    }

    /**
     * Relación con departamento a través de provincia
     */
    public function departamento()
    {
        return $this->hasOneThrough(
            Departamento::class,
            Provincia::class,
            'codigo_ubigeo', // Foreign key en provincia
            'codigo_ubigeo', // Foreign key en departamento
            'provincia_codigo', // Local key en distrito
            'departamento_codigo' // Local key en provincia
        );
    }

    /**
     * Relación con centros de salud
     */
    public function centrosSalud(): HasMany
    {
        return $this->hasMany(CentroSalud::class, 'distrito_codigo', 'codigo_ubigeo');
    }

    /**
     * Scope para distritos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
