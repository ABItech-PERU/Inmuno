<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dependiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'dependiente_user_id',
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'genero',
        'parentesco',
        'telefono',
        'observaciones',
        'activo'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function usuarioRegistrado()
    {
        return $this->belongsTo(User::class, 'dependiente_user_id');
    }

    public function aplicacionesVacunas()
    {
        // Si el dependiente se registró como usuario, usar sus aplicaciones
        if ($this->dependiente_user_id) {
            return $this->usuarioRegistrado->aplicacionesVacunas();
        }

        // Si no, buscar por número de documento en aplicaciones
        return AplicacionVacuna::whereHas('paciente', function($query) {
            $query->where('numero_documento', $this->numero_documento);
        });
    }

    public function recordatorios()
    {
        // Si el dependiente se registró como usuario, usar sus recordatorios
        if ($this->dependiente_user_id) {
            return $this->usuarioRegistrado->recordatorios();
        }

        // Si no, crear recordatorios asociados al tutor pero para el dependiente
        return Recordatorio::where('user_id', $this->tutor_id)
                          ->where('mensaje', 'LIKE', '%' . $this->nombres . ' ' . $this->apellidos . '%');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorTutor($query, $tutorId)
    {
        return $query->where('tutor_id', $tutorId);
    }

    public function scopePorParentesco($query, $parentesco)
    {
        return $query->where('parentesco', $parentesco);
    }

    // Métodos auxiliares
    public function getNombreCompletoAttribute()
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nacimiento)->age;
    }

    public function getEdadEnMesesAttribute()
    {
        return Carbon::parse($this->fecha_nacimiento)->diffInMonths(now());
    }

    public function esMenorDeEdad()
    {
        return $this->edad < 18;
    }

    /**
     * Vincular este dependiente con un usuario registrado
     */
    public function vincularConUsuario($userId)
    {
        $this->update(['dependiente_user_id' => $userId]);
    }

    /**
     * Desvincular el usuario registrado
     */
    public function desvincularUsuario()
    {
        $this->update(['dependiente_user_id' => null]);
    }
}
