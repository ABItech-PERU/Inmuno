<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosisVacuna extends Model
{
    use HasFactory;

    protected $fillable = [
        'esquema_vacunacion_id',
        'vacuna_id',
        'numero_dosis',
        'edad_aplicacion',
        'dias_despues_anterior',
        'es_refuerzo',
        'edad_minima_dias',
        'edad_maxima_dias',
        'observaciones',
        'justificacion_medica',
        'es_obligatoria',
        'activo'
    ];

    protected $casts = [
        'numero_dosis' => 'integer',
        'edad_aplicacion' => 'integer',
        'dias_despues_anterior' => 'integer',
        'es_refuerzo' => 'boolean',
        'es_obligatoria' => 'boolean',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function esquemaVacunacion()
    {
        return $this->belongsTo(EsquemaVacunacion::class);
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function aplicaciones()
    {
        return $this->hasMany(AplicacionVacuna::class);
    }

    // Scopes
    public function scopePorEsquema($query, $esquemaId)
    {
        return $query->where('esquema_vacunacion_id', $esquemaId);
    }

    public function scopePorVacuna($query, $vacunaId)
    {
        return $query->where('vacuna_id', $vacunaId);
    }

    public function scopeRefuerzos($query)
    {
        return $query->where('es_refuerzo', true);
    }

    public function scopePorEdad($query, $edadMeses)
    {
        return $query->where('edad_aplicacion', '<=', $edadMeses);
    }
}
