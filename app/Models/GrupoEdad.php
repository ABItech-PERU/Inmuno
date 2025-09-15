<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoEdad extends Model
{
    use HasFactory;

    protected $table = 'grupos_edad';

    protected $fillable = [
        'nombre',
        'descripcion',
        'orden',
        'edad_inicio_meses',
        'edad_fin_meses',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'edad_inicio_meses' => 'integer',
        'edad_fin_meses' => 'integer',
        'orden' => 'integer'
    ];

    /**
     * Scope para obtener solo grupos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para ordenar por el campo orden
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden', 'asc');
    }

    /**
     * Obtener solo los nombres de grupos activos
     */
    public static function getNombresActivos()
    {
        return static::activos()->ordenado()->pluck('nombre')->toArray();
    }

    /**
     * Obtener grupos activos con sus datos completos para formularios
     */
    public static function getGruposParaFormulario()
    {
        return static::activos()->ordenado()->get(['nombre', 'descripcion', 'edad_inicio_meses', 'edad_fin_meses'])
            ->map(function ($grupo) {
                return [
                    'nombre' => $grupo->nombre,
                    'descripcion' => $grupo->descripcion,
                    'edad_inicio' => $grupo->edad_inicio_meses,
                    'edad_fin' => $grupo->edad_fin_meses
                ];
            })->toArray();
    }
}
