<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Recordatorio;
use App\Models\Dependiente;
use App\Models\User;
use App\Models\Vacuna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;
use App\Jobs\EnviarRecordatorioJob;

class RecordatoriosController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Obtener filtros
        $filtroTipo = $request->get('tipo');
        $filtroEstado = $request->get('estado', 'todos');
        $filtroBusqueda = $request->get('busqueda');

        // Query base para recordatorios
        $query = Recordatorio::where('user_id', $user->id)
            ->with(['vacuna', 'dependiente']);

        // Aplicar filtro de búsqueda
        if ($filtroBusqueda) {
            $query->where(function ($q) use ($filtroBusqueda) {
                $q->where('titulo', 'like', '%' . $filtroBusqueda . '%')
                    ->orWhere('mensaje', 'like', '%' . $filtroBusqueda . '%')
                    ->orWhereHas('vacuna', function ($vq) use ($filtroBusqueda) {
                        $vq->where('nombre', 'like', '%' . $filtroBusqueda . '%');
                    })
                    ->orWhereHas('dependiente', function ($dq) use ($filtroBusqueda) {
                        $dq->where('nombres', 'like', '%' . $filtroBusqueda . '%')
                            ->orWhere('apellidos', 'like', '%' . $filtroBusqueda . '%');
                    });
            });
        }

        // Aplicar filtros
        if ($filtroTipo && $filtroTipo !== 'todos') {
            $query->where('tipo', $filtroTipo);
        }

        if ($filtroEstado && $filtroEstado !== 'todos') {
            $query->where('estado', $filtroEstado);
        }

        // Ordenación inteligente: primero los de hoy, luego los próximos, luego los pasados
        $recordatorios = $query->orderByRaw("
            CASE
                WHEN DATE(fecha_recordatorio) = CURDATE() THEN 1
                WHEN DATE(fecha_recordatorio) > CURDATE() THEN 2
                ELSE 3
            END ASC,
            fecha_recordatorio ASC
        ")
            ->paginate(10)
            ->withQueryString();

        // Obtener dependientes
        $dependientes = Dependiente::where('tutor_id', $user->id)
            ->orderBy('nombres')
            ->get()
            ->map(function ($dependiente) {
                return [
                    'id' => $dependiente->id,
                    'nombre_completo' => $dependiente->nombre_completo,
                    'nombres' => $dependiente->nombres,
                    'apellidos' => $dependiente->apellidos,
                    'parentesco' => $dependiente->parentesco,
                    'edad' => $dependiente->edad,
                    'genero' => $dependiente->genero,
                ];
            });

        // Estadísticas centradas en el paciente
        $estadisticas = [
            'total_recordatorios' => Recordatorio::where('user_id', $user->id)->count(),

            // Recordatorios para hoy (urgentes)
            'para_hoy' => Recordatorio::where('user_id', $user->id)
                ->whereDate('fecha_recordatorio', now()->toDateString())
                ->whereIn('estado', ['programado', 'es_hoy'])
                ->count(),

            // Recordatorios completados exitosamente
            'completados' => Recordatorio::where('user_id', $user->id)
                ->where('estado', 'hecho')
                ->count(),

            // Próximos recordatorios (futuros)
            'proximos' => Recordatorio::where('user_id', $user->id)
                ->where('fecha_recordatorio', '>', now()->toDateString())
                ->where('estado', 'programado')
                ->count(),

            // Recordatorios que requieren atención (no hechos y no aplicables)
            'pendientes_atencion' => Recordatorio::where('user_id', $user->id)
                ->whereIn('estado', ['no_hice', 'es_hoy'])
                ->count(),

            // Estadísticas adicionales para mejor contexto
            'vencidos' => Recordatorio::where('user_id', $user->id)
                ->whereDate('fecha_recordatorio', '<', now()->toDateString())
                ->whereIn('estado', ['programado', 'no_hice'])
                ->count(),

            'esta_semana' => Recordatorio::where('user_id', $user->id)
                ->whereBetween('fecha_recordatorio', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->whereIn('estado', ['programado', 'es_hoy'])
                ->count(),

            'total_dependientes' => Dependiente::where('tutor_id', $user->id)->count(),
        ];

        // Próximos recordatorios importantes (próximos 7 días)
        $proximosRecordatorios = Recordatorio::where('user_id', $user->id)
            ->with(['vacuna', 'dependiente'])
            ->whereIn('estado', ['programado', 'es_hoy'])
            ->where('fecha_recordatorio', '>=', now()->toDateString())
            ->where('fecha_recordatorio', '<=', now()->addDays(7)->toDateString())
            ->orderByRaw("
                CASE
                    WHEN DATE(fecha_recordatorio) = CURDATE() THEN 1
                    WHEN DATE(fecha_recordatorio) = DATE_ADD(CURDATE(), INTERVAL 1 DAY) THEN 2
                    ELSE 3
                END ASC,
                fecha_recordatorio ASC
            ")
            ->limit(5)
            ->get();

        // Normalizar la salida de fechas: enviar solo Y-m-d para evitar ambigüedades de zona horaria
        $recordatorios->getCollection()->transform(function ($r) {
            if ($r->fecha_recordatorio) {
                // $r->fecha_recordatorio es un Carbon (cast en el modelo)
                $r->fecha_recordatorio = $r->fecha_recordatorio->format('Y-m-d');
            }
            if ($r->hora_recordatorio) {
                if ($r->hora_recordatorio instanceof \DateTime) {
                    $r->hora_recordatorio = $r->hora_recordatorio->format('H:i');
                }
            }
            return $r;
        });

        $proximosRecordatorios = $proximosRecordatorios->map(function ($r) {
            if ($r->fecha_recordatorio) {
                $r->fecha_recordatorio = $r->fecha_recordatorio->format('Y-m-d');
            }
            if ($r->hora_recordatorio) {
                if ($r->hora_recordatorio instanceof \DateTime) {
                    $r->hora_recordatorio = $r->hora_recordatorio->format('H:i');
                }
            }
            return $r;
        });

        return Inertia::render('Paciente/Recordatorios/Index', [
            'recordatorios' => $recordatorios,
            'dependientes' => $dependientes,
            'estadisticas' => $estadisticas,
            'proximosRecordatorios' => $proximosRecordatorios,
            'filtros' => [
                'tipo' => $filtroTipo,
                'estado' => $filtroEstado,
                'busqueda' => $filtroBusqueda,
            ]
        ]);
    }

    /**
     * Mostrar un recordatorio específico
     */
    public function show($id)
    {
        $user = Auth::user();

        // Obtener el recordatorio con relaciones
        $recordatorio = Recordatorio::where('user_id', $user->id)
            ->with([
                'vacuna',
                'dependiente'
            ])
            ->findOrFail($id);

        // Calcular estadísticas del recordatorio
        // Crear fecha completa considerando hora si existe
        $fechaBase = Carbon::parse($recordatorio->fecha_recordatorio);

        if ($recordatorio->hora_recordatorio) {
            // Extraer solo la parte de tiempo si viene como datetime
            $hora = $recordatorio->hora_recordatorio;
            if ($hora instanceof \DateTime) {
                $hora = $hora->format('H:i:s');
            }
            $fechaCompleta = $fechaBase->copy()->setTimeFromTimeString($hora);
        } else {
            $fechaCompleta = $fechaBase->startOfDay();
        }

        $estadisticas = [
            'dias_restantes' => $this->calcularDiasRestantes($fechaCompleta, $recordatorio->estado),
            'es_urgente' => $this->esUrgente($fechaCompleta, $recordatorio->estado),
            'es_vencido' => $fechaCompleta->isPast(),
            'es_hoy' => $fechaCompleta->isToday(),
            'es_manana' => $fechaCompleta->isTomorrow(),
            'semana_actual' => $fechaCompleta->isCurrentWeek(),
            'puede_completar' => in_array($recordatorio->estado, ['programado', 'es_hoy']),
            'requiere_atencion' => in_array($recordatorio->estado, ['no_hice', 'es_hoy'])
        ];

        // Recordatorios relacionados (misma vacuna)
        $recordatoriosRelacionados = [];
        if ($recordatorio->vacuna_id) {
            $recordatoriosRelacionados = Recordatorio::where('user_id', $user->id)
                ->where('id', '!=', $recordatorio->id)
                ->where('vacuna_id', $recordatorio->vacuna_id)
                ->with(['vacuna'])
                ->orderBy('fecha_recordatorio', 'desc')
                ->limit(5)
                ->get();
        }

        return Inertia::render('Paciente/Recordatorios/Show', [
            'recordatorio' => (function ($r) {
                if ($r->fecha_recordatorio) $r->fecha_recordatorio = $r->fecha_recordatorio->format('Y-m-d');
                if ($r->hora_recordatorio && $r->hora_recordatorio instanceof \DateTime) $r->hora_recordatorio = $r->hora_recordatorio->format('H:i');
                return $r;
            })($recordatorio),
            'estadisticas' => $estadisticas,
            'recordatoriosRelacionados' => $recordatoriosRelacionados
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'dependiente_id' => 'nullable|exists:dependientes,id',
            'dosis_vacuna_id' => 'nullable|exists:dosis_vacunas,id',
            'vacuna_id' => 'nullable|exists:vacunas,id',
            'tipo' => ['required', 'in:vacuna_proxima,cita_programada,refuerzo_pendiente,recordatorio_personal'],
            'titulo' => 'required|string|max:255',
            'mensaje' => 'nullable|string',
            'fecha_recordatorio' => 'required|date|after_or_equal:today',
            'hora_recordatorio' => 'nullable|date_format:H:i',
        ]);

        // Si es para un dependiente, validar que pertenezca al usuario
        if ($validated['dependiente_id']) {
            $dependiente = Dependiente::where('tutor_id', $user->id)
                ->where('id', $validated['dependiente_id'])
                ->firstOrFail();
        }

        $recordatorio = Recordatorio::create([
            'user_id' => $user->id,
            'dependiente_id' => $validated['dependiente_id'],
            'dosis_vacuna_id' => $validated['dosis_vacuna_id'] ?? null,
            'vacuna_id' => $validated['vacuna_id'],
            'tipo' => $validated['tipo'],
            'titulo' => $validated['titulo'],
            'mensaje' => $validated['mensaje'],
            'fecha_recordatorio' => $validated['fecha_recordatorio'],
            'hora_recordatorio' => $validated['hora_recordatorio'] ?? null,
            'estado' => 'programado',
            'metodo_envio' => 'sistema'
        ]);

        Log::info("Nuevo recordatorio #{$recordatorio->id} creado para usuario #{$user->id}");

        // Enviar confirmación inmediata
        $this->enviarConfirmacion($recordatorio, $user);

        // Programar envío en hora exacta si aplica
        $this->programarEnvioExacto($recordatorio);

        return redirect()->route('paciente.recordatorios.index')
            ->with('success', 'Recordatorio creado exitosamente.');
    }

    public function edit(Recordatorio $recordatorio)
    {
        $user = Auth::user();


        // Verificar que el recordatorio pertenece al usuario
        if ($recordatorio->user_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        // Obtener dependientes del usuario
        $dependientes = Dependiente::where('tutor_id', $user->id)
            ->orderBy('nombres')
            ->get()
            ->map(function ($dependiente) {
                return [
                    'id' => $dependiente->id,
                    'nombre_completo' => $dependiente->nombre_completo,
                ];
            });

        // Obtener vacunas
        $vacunas = Vacuna::orderBy('nombre')->get();

        return Inertia::render('Paciente/Recordatorios/Edit', [
            'recordatorio' => (function ($r) {
                if ($r->fecha_recordatorio) $r->fecha_recordatorio = $r->fecha_recordatorio->format('Y-m-d');
                if ($r->hora_recordatorio && $r->hora_recordatorio instanceof \DateTime) $r->hora_recordatorio = $r->hora_recordatorio->format('H:i');
                return $r;
            })($recordatorio),
            'dependientes' => $dependientes,
            'vacunas' => $vacunas
        ]);
    }

    public function update(Request $request, Recordatorio $recordatorio)
    {
        $user = Auth::user();

        if ($recordatorio->user_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'dependiente_id' => 'nullable|exists:dependientes,id',
            'vacuna_id' => 'nullable|exists:vacunas,id',
            'tipo' => ['required', 'in:vacuna_proxima,cita_programada,refuerzo_pendiente,recordatorio_personal'],
            'titulo' => 'required|string|max:255',
            'mensaje' => 'nullable|string',
            'fecha_recordatorio' => 'required|date',
            'hora_recordatorio' => 'nullable|date_format:H:i',
            'estado' => ['required', 'in:programado,es_hoy,hecho,no_hice,no_aplica']
        ]);

        // Si es para un dependiente, validar que pertenezca al usuario
        if ($validated['dependiente_id']) {
            $dependiente = Dependiente::where('id', $validated['dependiente_id'])
                ->where('tutor_id', $user->id)
                ->first();

            if (!$dependiente) {
                return back()->withErrors([
                    'dependiente_id' => 'El dependiente seleccionado no es válido.'
                ]);
            }
        }

        $recordatorio->update($validated);

        return redirect()->route('paciente.recordatorios.index')
            ->with('success', 'Recordatorio actualizado exitosamente.');
    }

    public function destroy(Recordatorio $recordatorio)
    {
        $user = Auth::user();

        if ($recordatorio->user_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $recordatorio->delete();

        return redirect()->route('paciente.recordatorios.index')
            ->with('success', 'Recordatorio eliminado exitosamente.');
    }

    public function marcarCompletado(Recordatorio $recordatorio)
    {
        $user = Auth::user();

        if ($recordatorio->user_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $recordatorio->update([
            'estado' => 'hecho'
        ]);

        return redirect()->route('paciente.recordatorios.index')
            ->with('success', 'Recordatorio marcado como completado.');
    }

    public function dependientes()
    {
        $user = Auth::user();

        $dependientes = Dependiente::where('tutor_id', $user->id)
            ->orderBy('nombres')
            ->paginate(10);

        return Inertia::render('Paciente/Recordatorios/Dependientes', [
            'dependientes' => $dependientes,
        ]);
    }

    public function storeDependiente(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|in:DNI,CE,Pasaporte,CNE',
            'numero_documento' => 'required|string|max:20|unique:dependientes',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'genero' => ['required', 'in:M,F,O'],
            'parentesco' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        $validated['tutor_id'] = $user->id;
        $validated['activo'] = true;

        $dependiente = Dependiente::create($validated);

        return redirect()->route('paciente.recordatorios.dependientes')
            ->with('success', 'Dependiente registrado exitosamente.');
    }

    public function updateDependiente(Request $request, Dependiente $dependiente)
    {
        $user = Auth::user();

        if ($dependiente->tutor_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|string|in:DNI,CE,Pasaporte,CNE',
            'numero_documento' => 'required|string|max:20|unique:dependientes,numero_documento,' . $dependiente->id,
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'genero' => ['required', 'in:M,F,O'],
            'parentesco' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        $dependiente->update($validated);

        return redirect()->route('paciente.recordatorios.dependientes')
            ->with('success', 'Dependiente actualizado exitosamente.');
    }

    public function destroyDependiente(Dependiente $dependiente)
    {
        $user = Auth::user();

        if ($dependiente->tutor_id !== $user->id) {
            abort(403, 'No autorizado.');
        }

        $dependiente->delete();

        return redirect()->route('paciente.recordatorios.dependientes')
            ->with('success', 'Dependiente eliminado exitosamente.');
    }

    /**
     * Calcular días restantes hasta la fecha del recordatorio
     */
    private function calcularDiasRestantes($fecha, $estado = null)
    {
        // Si el recordatorio ya está completado, no mostrar días restantes
        if ($estado === 'hecho') {
            return 'Completado';
        }

        if ($estado === 'no_aplica') {
            return 'No aplica';
        }

        $hoy = Carbon::now();
        $fechaRecordatorio = Carbon::parse($fecha);

        // Obtener días completos de diferencia
        $diferencia = $hoy->startOfDay()->diffInDays($fechaRecordatorio->startOfDay(), false);

        if ($diferencia === 0) {
            // Es hoy, verificar si ya pasó la hora
            if ($fechaRecordatorio->isPast()) {
                $horasAtrasadas = $hoy->diffInHours($fechaRecordatorio);
                if ($horasAtrasadas < 24) {
                    return 'Hoy (ya pasó)';
                } else {
                    return 'Ayer';
                }
            } else {
                return 'Hoy';
            }
        }

        if ($diferencia === 1) return 'Mañana';
        if ($diferencia === -1) return 'Ayer';
        if ($diferencia > 1) return "En {$diferencia} días";
        if ($diferencia < -1) return 'Hace ' . abs($diferencia) . ' días';

        return $diferencia;
    }

    /**
     * Determinar si un recordatorio es urgente
     */
    private function esUrgente($fecha, $estado = null)
    {
        // Si ya está completado o no aplica, no es urgente
        if (in_array($estado, ['hecho', 'no_aplica'])) {
            return false;
        }

        $fechaRecordatorio = Carbon::parse($fecha);
        $hoy = Carbon::now();

        // Es urgente si es hoy, mañana o ya pasó
        return $fechaRecordatorio->isToday() ||
            $fechaRecordatorio->isTomorrow() ||
            $fechaRecordatorio->isPast() ||
            $fechaRecordatorio->diffInDays($hoy) <= 1;
    }

    /**
     * Envía la confirmación de creación del recordatorio
     */
    private function enviarConfirmacion(Recordatorio $recordatorio, User $user)
    {
        $enviarSincrono = config('app.env') === 'local' || config('queue.default') === 'sync';

        if ($enviarSincrono) {
            try {
                Log::info("Enviando confirmacion sincrona para recordatorio #{$recordatorio->id}");
                Notification::send($user, new \App\Notifications\RecordatorioNotification($recordatorio, 'created'));
                Log::info("Confirmacion sincrona enviada para recordatorio #{$recordatorio->id}");
            } catch (\Throwable $e) {
                Log::error("Error en confirmacion sincrona #{$recordatorio->id}: {$e->getMessage()}");
            }
        } else {
            try {
                Log::info("Encolando confirmacion asincrona para recordatorio #{$recordatorio->id}");
                EnviarRecordatorioJob::dispatch($recordatorio->id, 'created')->onQueue('emails');
                Log::info("Job de confirmacion encolado para recordatorio #{$recordatorio->id}");
            } catch (\Throwable $e) {
                Log::error("Error encolando confirmacion #{$recordatorio->id}: {$e->getMessage()}");
                // Fallback síncrono
                try {
                    Log::info("Fallback: Enviando confirmacion sincrona para recordatorio #{$recordatorio->id}");
                    Notification::send($user, new \App\Notifications\RecordatorioNotification($recordatorio, 'created'));
                    Log::info("Confirmacion fallback enviada para recordatorio #{$recordatorio->id}");
                } catch (\Throwable $ex) {
                    Log::error("Error en fallback de confirmacion #{$recordatorio->id}: {$ex->getMessage()}");
                }
            }
        }
    }

    /**
     * Programa el envío en hora exacta si aplica
     */
    private function programarEnvioExacto(Recordatorio $recordatorio)
    {
        if (!$recordatorio->hora_recordatorio) {
            return;
        }

        try {
            $fechaCompleta = Carbon::parse($recordatorio->fecha_recordatorio, config('app.timezone'))
                ->setTimeFromTimeString($recordatorio->hora_recordatorio);

            if ($fechaCompleta->isFuture()) {
                if (config('queue.default') !== 'sync') {
                    EnviarRecordatorioJob::dispatch($recordatorio->id, 'reminder')
                        ->delay($fechaCompleta)
                        ->onQueue('emails');
                    Log::info("Job programado para recordatorio #{$recordatorio->id} a las {$fechaCompleta->format('Y-m-d H:i:s T')}");
                } else {
                    Log::info("Queue es sync, no se programa job delayed para recordatorio #{$recordatorio->id}");
                }
            }
        } catch (\Throwable $e) {
            Log::error("Error programando job delayed para recordatorio #{$recordatorio->id}: {$e->getMessage()}");
        }
    }
}
