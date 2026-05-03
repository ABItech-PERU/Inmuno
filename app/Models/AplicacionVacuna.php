<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AplicacionVacuna extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    'dependiente_id',
        'vacuna_id',
        'dosis_vacuna_id',
        'medico_id',
        'centro_salud_id',
        'numero_dosis',
        'fecha_aplicacion',
        'lote_vacuna',
        'fecha_vencimiento',
        'observaciones',
        'reacciones_adversas',
        'proxima_dosis'
    ];

    protected $casts = [
        'numero_dosis' => 'integer',
        'fecha_aplicacion' => 'date',
        'fecha_vencimiento' => 'date',
        'proxima_dosis' => 'date',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dependiente()
    {
        return $this->belongsTo(Dependiente::class, 'dependiente_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function dosisVacuna()
    {
        return $this->belongsTo(DosisVacuna::class);
    }

    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class);
    }

    // Scopes
    public function scopePorPaciente($query, $usuarioId)
    {
        return $query->where('user_id', $usuarioId);
    }

    public function scopePorMedico($query, $medicoId)
    {
        return $query->where('medico_id', $medicoId);
    }

    public function scopePorVacuna($query, $vacunaId)
    {
        return $query->where('vacuna_id', $vacunaId);
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin = null)
    {
        $query->where('fecha_aplicacion', '>=', $fechaInicio);
        if ($fechaFin) {
            $query->where('fecha_aplicacion', '<=', $fechaFin);
        }
        return $query;
    }

    public function scopeConProximaDosis($query)
    {
        return $query->whereNotNull('proxima_dosis');
    }
}
