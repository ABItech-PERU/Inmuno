<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsquemaVacunacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'grupo_edad',
        'edad_inicio',
        'edad_fin',
        'obligatorio',
        'activo'
    ];

    protected $casts = [
        'obligatorio' => 'boolean',
        'activo' => 'boolean',
        'edad_inicio' => 'integer',
        'edad_fin' => 'integer',
    ];

    // Relaciones
    public function dosisVacunas()
    {
        return $this->hasMany(DosisVacuna::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeObligatorios($query)
    {
        return $query->where('obligatorio', true);
    }

    public function scopePorGrupoEdad($query, $grupoEdad)
    {
        return $query->where('grupo_edad', $grupoEdad);
    }

    public function scopePorEdad($query, $edadMeses)
    {
        return $query->where('edad_inicio', '<=', $edadMeses)
                    ->where(function($q) use ($edadMeses) {
                        $q->where('edad_fin', '>=', $edadMeses)
                          ->orWhereNull('edad_fin');
                    });
    }
}
