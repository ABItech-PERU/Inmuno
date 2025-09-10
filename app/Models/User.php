<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'password',
        'google_id',
        'estado',
        'se_registro',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'fecha_emision',
        'genero',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'date',
        ];
    }

    // Relaciones del sistema de vacunación

    /**
     * Aplicaciones de vacunas como paciente
     */
    public function aplicacionesVacunas()
    {
        return $this->hasMany(AplicacionVacuna::class, 'user_id');
    }

    /**
     * Aplicaciones de vacunas aplicadas como médico
     */
    public function vacunasAplicadas()
    {
        return $this->hasMany(AplicacionVacuna::class, 'medico_id');
    }

    /**
     * Citas como paciente
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    /**
     * Citas atendidas como médico
     */
    public function citasAtendidas()
    {
        return $this->hasMany(Cita::class, 'medico_id');
    }

    /**
     * Recordatorios del usuario
     */
    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    // Métodos auxiliares

    /**
     * Verificar si el usuario es médico
     */
    public function esMedico()
    {
        return $this->hasRole('MEDICO');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdministrador()
    {
        return $this->hasRole('ADMINISTRADOR');
    }

    /**
     * Verificar si el usuario es paciente
     */
    public function esPaciente()
    {
        return $this->hasRole('PACIENTE');
    }

    /**
     * Obtener edad en meses
     */
    public function getEdadEnMeses()
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }

        $fechaNacimiento = \Carbon\Carbon::parse($this->fecha_nacimiento);
        return $fechaNacimiento->diffInMonths(now());
    }
}
