<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fabricante',
        'tipo',
        'dosis_requeridas',
        'intervalo_entre_dosis',
        'edad_minima',
        'edad_maxima',
        'contraindicaciones',
        'efectos_secundarios',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'dosis_requeridas' => 'integer',
        'intervalo_entre_dosis' => 'integer',
        'edad_minima' => 'integer',
        'edad_maxima' => 'integer',
    ];

    // Relaciones
    public function dosisVacunas()
    {
        return $this->hasMany(DosisVacuna::class);
    }

    public function aplicaciones()
    {
        return $this->hasMany(AplicacionVacuna::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopePorEdad($query, $edadMeses)
    {
        return $query->where(function($q) use ($edadMeses) {
            $q->where('edad_minima', '<=', $edadMeses)
              ->where(function($subQ) use ($edadMeses) {
                  $subQ->where('edad_maxima', '>=', $edadMeses)
                       ->orWhereNull('edad_maxima');
              });
        });
    }
}
