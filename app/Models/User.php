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
        'fecha_nacimiento_formatted',
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
            'estado' => 'boolean',
        ];
    }

    /**
     * Get the formatted birth date for form inputs.
     */
    protected function getFechaNacimientoFormattedAttribute()
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->format('Y-m-d') : null;
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

    /**
     * Dependientes que este usuario tutora (hijos, apoderados)
     */
    public function dependientes()
    {
        return $this->hasMany(Dependiente::class, 'tutor_id');
    }

    /**
     * Relación si este usuario es dependiente de alguien
     */
    public function esDependienteDe()
    {
        return $this->hasOne(Dependiente::class, 'dependiente_user_id');
    }

    /**
     * Obtener todos los tutores de este usuario (si es que es dependiente)
     */
    public function tutores()
    {
        return $this->hasManyThrough(
            User::class,
            Dependiente::class,
            'dependiente_user_id', // Foreign key en dependientes
            'id', // Foreign key en users (tutores)
            'id', // Local key en este user
            'tutor_id' // Local key en dependientes
        );
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
     * Verificar si este usuario es dependiente de otro
     */
    public function esDependiente()
    {
        return $this->esDependienteDe()->exists();
    }

    /**
     * Mutator para name: guardar en mayúsculas UTF-8
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value === null ? null : mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Mutator para apellidos: guardar en mayúsculas UTF-8
     */
    public function setApellidosAttribute($value)
    {
        $this->attributes['apellidos'] = $value === null ? null : mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Verificar si este usuario tiene dependientes
     */
    public function tieneDependientes()
    {
        return $this->dependientes()->where('activo', true)->exists();
    }

    /**
     * Obtener todos los recordatorios incluyendo los de dependientes
     */
    public function recordatoriosCompletos()
    {
        $recordatoriosPropios = $this->recordatorios();

        // Si tiene dependientes, también incluir recordatorios relacionados
        if ($this->tieneDependientes()) {
            $dependientes = $this->dependientes()->activos()->get();
            $recordatoriosDependientes = collect();

            foreach ($dependientes as $dependiente) {
                // Buscar recordatorios que mencionen al dependiente
                $recordatoriosDep = Recordatorio::where('user_id', $this->id)
                    ->where(function($query) use ($dependiente) {
                        $query->where('titulo', 'LIKE', '%' . $dependiente->nombres . '%')
                              ->orWhere('mensaje', 'LIKE', '%' . $dependiente->nombres . ' ' . $dependiente->apellidos . '%');
                    });

                $recordatoriosDependientes = $recordatoriosDependientes->merge($recordatoriosDep->get());
            }

            return $recordatoriosPropios->union($recordatoriosDependientes);
        }

        return $recordatoriosPropios;
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
