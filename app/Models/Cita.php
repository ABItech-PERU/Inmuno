<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'centro_salud_id',
        'vacuna_id',
        'fecha_hora',
        'tipo_cita',
        'estado',
        'motivo',
        'observaciones'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function centroSalud()
    {
        return $this->belongsTo(CentroSalud::class);
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    // Scopes
    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    public function scopePorMedico($query, $medicoId)
    {
        return $query->where('medico_id', $medicoId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha_hora', $fecha);
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_hora', now()->toDateString());
    }

    public function scopeProgramadas($query)
    {
        return $query->where('estado', 'programada');
    }

    public function scopeProximas($query, $dias = 7)
    {
        return $query->whereBetween('fecha_hora', [
            now(),
            now()->addDays($dias)
        ]);
    }
}
