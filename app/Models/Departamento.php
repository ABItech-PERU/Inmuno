<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $fillable = [
        'codigo_ubigeo',
        'nombre',
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
     * Relación con provincias
     */
    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class, 'departamento_codigo', 'codigo_ubigeo');
    }

    /**
     * Scope para departamentos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
