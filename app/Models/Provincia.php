<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    protected $fillable = [
        'codigo_ubigeo',
        'nombre',
        'departamento_codigo',
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
     * Relación con departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_codigo', 'codigo_ubigeo');
    }

    /**
     * Relación con distritos
     */
    public function distritos(): HasMany
    {
        return $this->hasMany(Distrito::class, 'provincia_codigo', 'codigo_ubigeo');
    }

    /**
     * Scope para provincias activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
