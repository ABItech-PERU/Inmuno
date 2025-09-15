<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dependiente_id',
        'vacuna_id',
        'cita_id',
        'tipo',
        'titulo',
        'mensaje',
        'fecha_recordatorio',
        'hora_recordatorio',
        'estado',
        'metodo_envio',
        'enviado_en',
        'leido_en'
    ];

    protected $casts = [
        'fecha_recordatorio' => 'date',
        'enviado_en' => 'datetime',
        'leido_en' => 'datetime',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacuna()
    {
        return $this->belongsTo(Vacuna::class);
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function dependiente()
    {
        return $this->belongsTo(Dependiente::class);
    }

    // Scopes
    public function scopePorUsuario($query, $usuarioId)
    {
        return $query->where('user_id', $usuarioId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeParaHoy($query)
    {
        return $query->whereDate('fecha_recordatorio', now()->toDateString());
    }

    public function scopeVencidos($query)
    {
        return $query->where('fecha_recordatorio', '<', now()->toDateString())
                    ->where('estado', 'pendiente');
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // Métodos auxiliares
    public function marcarComoEnviado()
    {
        $this->update([
            'estado' => 'enviado',
            'enviado_en' => now()
        ]);
    }

    public function marcarComoLeido()
    {
        $this->update([
            'estado' => 'leido',
            'leido_en' => now()
        ]);
    }
}
