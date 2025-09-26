<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\EsquemaVacunacion;
use App\Models\AplicacionVacuna;
use App\Models\DosisVacuna;
use App\Models\CentroSalud;
use App\Models\Dependiente;
use App\Models\Recordatorio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;

class EsquemaVacunacionController extends Controller
{
    /**
     * Mostrar el esquema de vacunación del paciente y sus dependientes
     */
    public function index()
    {
        $user = Auth::user();

        // Verificar si el usuario tiene fecha de nacimiento
        if (!$user->fecha_nacimiento) {
            return Inertia::render('Paciente/EsquemaVacunacion/Index', [
                'usuario' => [
                    'datos' => $user,
                    'edad_meses' => null,
                    'esquemas' => collect([])
                ],
                'dependientes' => collect([]),
                'error' => 'Por favor, actualiza tu fecha de nacimiento en tu perfil para ver tu esquema de vacunación.'
            ]);
        }

        // Obtener edad del usuario en meses calculando directamente
        if ($user->fecha_nacimiento) {
            $fechaNacimiento = Carbon::parse($user->fecha_nacimiento);
            $edadUsuario = intval($fechaNacimiento->diffInMonths(Carbon::now()));
        } else {
            Log::info('Usuario sin fecha de nacimiento: ' . $user->id);
            $edadUsuario = null;
        }

        // Debug: verificar que el cálculo es correcto
        if (is_null($edadUsuario)) {
            Log::info('No se pudo calcular la edad para el usuario: ' . $user->id);
        }

        // Obtener esquemas aplicables para el usuario
        $esquemasUsuario = $this->obtenerEsquemasParaPersona($user, $edadUsuario);

        // Obtener dependientes activos usando consulta directa
        $dependientes = Dependiente::where('tutor_id', $user->id)
                                   ->where('activo', true)
                                   ->get();

        // Procesar esquemas para cada dependiente
        $dependientesConEsquemas = $dependientes->map(function ($dependiente) {
            // Calcular edad del dependiente en meses
            if ($dependiente->fecha_nacimiento) {
                $fechaNacimiento = Carbon::parse($dependiente->fecha_nacimiento);
                $edadDependiente = intval($fechaNacimiento->diffInMonths(Carbon::now()));
            } else {
                $edadDependiente = 0; // Si no tiene fecha, asumir recién nacido
            }

            $esquemas = $this->obtenerEsquemasParaPersona($dependiente, $edadDependiente, true);

            return [
                'dependiente' => $dependiente,
                'edad_meses' => $edadDependiente,
                'esquemas' => $esquemas
            ];
        })->toArray();

        return Inertia::render('Paciente/EsquemaVacunacion/Index', [
            'usuario' => [
                'datos' => $user,
                'edad_meses' => $edadUsuario,
                'esquemas' => $esquemasUsuario
            ],
            'dependientes' => $dependientesConEsquemas
        ]);
    }

    /**
     * Mostrar el esquema de vacunación detallado de una persona específica
     */
    public function show($persona_tipo, $persona_id = null)
    {
        $user = Auth::user();

        // Determinar la persona y sus datos
        if ($persona_tipo === 'paciente') {
            $persona = $user;
            // Construir nombre completo de forma robusta:
            // - Si existen nombres y apellidos: "nombres apellidos"
            // - Si solo existen nombres: usar nombres
            // - Si solo existen apellidos: usar apellidos
            // - Si ninguno existe, fallback a name
            // En el modelo User el campo de nombre se llama `name`, no `nombres`
            $nombres = trim($user->name ?? '');
            $apellidos = trim($user->apellidos ?? '');
            if ($nombres !== '' && $apellidos !== '') {
                $nombreCompleto = $nombres . ' ' . $apellidos;
            } elseif ($nombres !== '') {
                $nombreCompleto = $nombres;
            } elseif ($apellidos !== '') {
                $nombreCompleto = $apellidos;
            } else {
                $nombreCompleto = $user->name;
            }
            $esUsuario = true;
        } else {
            // Verificar que el dependiente pertenece al usuario
            $persona = Dependiente::where('tutor_id', $user->id)
                                 ->where('id', $persona_id)
                                 ->firstOrFail();
            $nombreCompleto = $persona->nombres . ' ' . $persona->apellidos;
            $esUsuario = false;
        }

        // Calcular edad en meses
        if ($persona->fecha_nacimiento) {
            $fechaNacimiento = Carbon::parse($persona->fecha_nacimiento);
            $edadMeses = intval($fechaNacimiento->diffInMonths(Carbon::now()));
            $edadAños = intval($edadMeses / 12);
            $mesesRestantes = $edadMeses % 12;
        } else {
            $edadMeses = 0;
            $edadAños = 0;
            $mesesRestantes = 0;
        }

    // Obtener todos los esquemas (mostrar agrupado por esquema). No filtrar por edad aquí para la vista detallada
    $esquemas = $this->obtenerEsquemasParaPersona($persona, $edadMeses, !$esUsuario, true);

        // Obtener histórico completo de aplicaciones
        $aplicacionesHistorico = [];
        if ($esUsuario) {
            $aplicacionesHistorico = AplicacionVacuna::where('user_id', $persona->id)
                ->with(['vacuna', 'centroSalud'])
                ->orderBy('fecha_aplicacion', 'desc')
                ->get();
        } else {
            // Para dependientes, usar el ID del usuario registrado o del tutor
            $pacienteId = $persona->dependiente_user_id ?? $user->id;
            $aplicacionesHistorico = AplicacionVacuna::where('user_id', $pacienteId)
                ->with(['vacuna', 'centroSalud'])
                ->orderBy('fecha_aplicacion', 'desc')
                ->get();
        }

        // Calcular estadísticas generales
        $totalDosis = 0;
        $dosisAplicadas = 0;
        $dosisVencidas = 0;
        $proximaVacuna = null;

        foreach ($esquemas as $esquema) {
            $totalDosis += $esquema['dosis']->count();
            $dosisAplicadas += $esquema['dosis']->filter(fn($d) => $d['aplicada'])->count();

            // Buscar próxima vacuna
            $proximaDosis = $esquema['dosis']->filter(function($d) {
                return !$d['aplicada'] && $d['puede_aplicar'] && $d['dias_para_aplicacion'] >= 0;
            })->sortBy('dias_para_aplicacion')->first();

            if ($proximaDosis && (!$proximaVacuna || $proximaDosis['dias_para_aplicacion'] < $proximaVacuna['dias_para_aplicacion'])) {
                $proximaVacuna = $proximaDosis;
                $proximaVacuna['esquema_nombre'] = $esquema['esquema']->nombre;
            }
        }

        $porcentajeCompleto = $totalDosis > 0 ? round(($dosisAplicadas / $totalDosis) * 100, 1) : 0;

        return Inertia::render('Paciente/EsquemaVacunacion/Show', [
            'persona' => [
                'datos' => $persona,
                'nombre_completo' => $nombreCompleto,
                'es_usuario' => $esUsuario,
                'edad_meses' => $edadMeses,
                'edad_años' => $edadAños,
                'meses_restantes' => $mesesRestantes,
            ],
            'esquemas' => $esquemas,
            'aplicaciones_historico' => $aplicacionesHistorico,
            'estadisticas' => [
                'total_dosis' => $totalDosis,
                'dosis_aplicadas' => $dosisAplicadas,
                'dosis_pendientes' => $totalDosis - $dosisAplicadas,
                'porcentaje_completo' => $porcentajeCompleto,
                'proxima_vacuna' => $proximaVacuna
            ]
        ]);
    }

    /**
     * Marcar una dosis como aplicada
     */
    public function marcarAplicada(Request $request)
    {
        $request->validate([
            'dosis_id' => 'required|exists:dosis_vacunas,id',
            'persona_tipo' => 'required|in:usuario,dependiente',
            'persona_id' => 'required|integer',
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'centro_salud_id' => 'nullable|exists:centros_salud,id',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $dosisVacuna = DosisVacuna::findOrFail($request->dosis_id);

        // Determinar el paciente
        if ($request->persona_tipo === 'usuario') {
            $pacienteId = $user->id;
        } else {
            // Verificar que el dependiente pertenece al usuario usando consulta directa
            $dependiente = Dependiente::where('tutor_id', $user->id)
                                     ->where('id', $request->persona_id)
                                     ->firstOrFail();
            $pacienteId = $dependiente->dependiente_user_id ?? $user->id; // Si no tiene user registrado, usar el tutor
        }

        // Verificar que no exista ya una aplicación para esta dosis
        $existeAplicacion = AplicacionVacuna::where('user_id', $pacienteId)
            ->where('vacuna_id', $dosisVacuna->vacuna_id)
            ->where('numero_dosis', $dosisVacuna->numero_dosis)
            ->exists();

        if ($existeAplicacion) {
            return back()->with('error', 'Esta dosis ya está marcada como aplicada.');
        }

        // Crear la aplicación
        AplicacionVacuna::create([
            'user_id' => $pacienteId,
            'vacuna_id' => $dosisVacuna->vacuna_id,
            'medico_id' => null, // Marcado por el paciente
            'centro_salud_id' => $request->centro_salud_id,
            'fecha_aplicacion' => $request->fecha_aplicacion,
            'numero_dosis' => $dosisVacuna->numero_dosis,
            'lote_vacuna' => null,
            'observaciones' => $request->observaciones,
            'reaccion_adversa' => null
        ]);

        return back()->with('message', 'Dosis marcada como aplicada exitosamente.');
    }

    /**
     * Obtener centros de salud activos para el selector
     */
    public function getCentrosSalud()
    {
        $centros = CentroSalud::activos()
            ->select('id', 'nombre', 'direccion', 'distrito')
            ->orderBy('nombre')
            ->get();

        return response()->json($centros);
    }

    /**
     * Obtener esquemas de vacunación para una persona (usuario o dependiente)
     */
    private function obtenerEsquemasParaPersona($persona, $edadMeses, $esDependiente = false, $ignorarEdad = false)
    {
        // Si no hay edad definida y no indicamos ignorar el filtro de edad, retornar colección vacía
        if ($edadMeses === null && !$ignorarEdad) {
            return collect([]);
        }

        // Obtener todos los esquemas activos primero
        $esquemas = EsquemaVacunacion::where('activo', true)
            ->with(['dosisVacunas' => function($query) {
                $query->where('activo', true)
                      ->with('vacuna')
                      ->orderBy('edad_aplicacion')
                      ->orderBy('numero_dosis');
            }])
            ->get();

        // Filtrar por edad en PHP para evitar problemas de consulta, a menos que se indique ignorar el filtro
        if ($ignorarEdad) {
            $esquemasFiltrados = $esquemas;
        } else {
            $esquemasFiltrados = $esquemas->filter(function($esquema) use ($edadMeses) {
                $cumpleEdadInicio = $esquema->edad_inicio === null || $esquema->edad_inicio <= $edadMeses;
                $cumpleEdadFin = $esquema->edad_fin === null || $esquema->edad_fin >= $edadMeses;
                return $cumpleEdadInicio && $cumpleEdadFin;
            });
        }

        // Cargar recordatorios relevantes del usuario (y dependientes) para poder marcar dosis con recordatorio
        $userId = Auth::id();
        // Agrupar por dosis_vacuna_id si existe, y también por vacuna_id para fallback
        $recordatoriosUsuario = Recordatorio::where('user_id', $userId)
            ->whereIn('estado', ['programado', 'es_hoy', 'no_hice'])
            ->get();

        $recordByDosis = $recordatoriosUsuario->filter(fn($r) => $r->dosis_vacuna_id)->groupBy('dosis_vacuna_id');
        $recordByVacuna = $recordatoriosUsuario->filter(fn($r) => !$r->dosis_vacuna_id)->groupBy('vacuna_id');

        // Calcular la primera dosis pendiente por vacuna GLOBAL (no por esquema) para el fallback
        $firstPendingByVacunaGlobal = [];
        // Recolectar todas las dosis de los esquemas filtrados
        $allDosis = $esquemasFiltrados->flatMap(function($esq) {
            return $esq->dosisVacunas;
        });
        foreach ($allDosis as $dItem) {
            $vacId = $dItem->vacuna_id ?? null;
            if ($vacId === null) continue;
            $apForD = $this->buscarAplicacion($persona, $dItem, $esDependiente);
            if ($apForD === null) {
                if (!isset($firstPendingByVacunaGlobal[$vacId]) || $dItem->numero_dosis < $firstPendingByVacunaGlobal[$vacId]) {
                    $firstPendingByVacunaGlobal[$vacId] = $dItem->numero_dosis;
                }
            }
        }

    // Procesar cada esquema para determinar el estado de las dosis
        return $esquemasFiltrados->map(function ($esquema) use ($persona, $esDependiente, $recordatoriosUsuario, $recordByDosis, $recordByVacuna, $firstPendingByVacunaGlobal) {

            $dosisConEstado = $esquema->dosisVacunas->map(function ($dosis) use ($persona, $esDependiente, $recordatoriosUsuario, $firstPendingByVacunaGlobal, $recordByDosis, $recordByVacuna) {
                $aplicacion = $this->buscarAplicacion($persona, $dosis, $esDependiente);
                $vacunaId = $dosis->vacuna_id ?? null;
                $tieneRecordatorio = false;
                $primerRecordatorio = null;

                // 1) Buscar recordatorio directo por dosis_vacuna_id
                if (isset($recordByDosis[$dosis->id]) && $recordByDosis[$dosis->id]->count() > 0) {
                    $tieneRecordatorio = true;
                    $primerRecordatorio = $recordByDosis[$dosis->id]->first();
                } else {
                    // 2) Fallback: buscar por vacuna_id y marcar sólo si es la primera pendiente para esa vacuna
                    if ($vacunaId !== null && isset($recordByVacuna[$vacunaId]) && $recordByVacuna[$vacunaId]->count() > 0) {
                        if (isset($firstPendingByVacunaGlobal[$vacunaId]) && $dosis->numero_dosis == $firstPendingByVacunaGlobal[$vacunaId]) {
                            $tieneRecordatorio = true;
                            $primerRecordatorio = $recordByVacuna[$vacunaId]->first();
                        }
                    }
                }

                return [
                    'dosis' => $dosis,
                    'aplicada' => $aplicacion !== null,
                    'aplicacion' => $aplicacion,
                    'puede_aplicar' => $this->puedeAplicarDosis($persona, $dosis, $esDependiente),
                    'dias_para_aplicacion' => $this->calcularDiasParaAplicacion($persona, $dosis, $esDependiente),
                    // Marcar si existe un recordatorio pendiente/programado para esta vacuna (solo la dosis relevante)
                    'tiene_recordatorio' => $tieneRecordatorio,
                    'recordatorio' => $primerRecordatorio,
                ];
            });

            // Calcular estadísticas del esquema
            $totalDosis = $dosisConEstado->count();
            $dosisAplicadas = $dosisConEstado->filter(fn($d) => $d['aplicada'])->count();
            $porcentajeCompleto = $totalDosis > 0 ? round(($dosisAplicadas / $totalDosis) * 100, 1) : 0;

            return [
                'esquema' => $esquema,
                'dosis' => $dosisConEstado,
                'estadisticas' => [
                    'total' => $totalDosis,
                    'aplicadas' => $dosisAplicadas,
                    'pendientes' => $totalDosis - $dosisAplicadas,
                    'porcentaje_completo' => $porcentajeCompleto
                ]
            ];
        })->toArray();
    }

    /**
     * Buscar si existe una aplicación para una dosis específica
     */
    private function buscarAplicacion($persona, $dosis, $esDependiente)
    {
        if ($esDependiente) {
            // Para dependientes, buscar por user_id si está registrado, o por documento
            if ($persona->dependiente_user_id) {
                return AplicacionVacuna::where('user_id', $persona->dependiente_user_id)
                    ->where('vacuna_id', $dosis->vacuna_id)
                    ->where('numero_dosis', $dosis->numero_dosis)
                    ->first();
            } else {
                // Buscar por número de documento en la tabla de aplicaciones
                return AplicacionVacuna::whereHas('paciente', function($query) use ($persona) {
                    $query->where('numero_documento', $persona->numero_documento);
                })
                ->where('vacuna_id', $dosis->vacuna_id)
                ->where('numero_dosis', $dosis->numero_dosis)
                ->first();
            }
        } else {
            // Para el usuario principal
            return AplicacionVacuna::where('user_id', $persona->id)
                ->where('vacuna_id', $dosis->vacuna_id)
                ->where('numero_dosis', $dosis->numero_dosis)
                ->first();
        }
    }

    /**
     * Determinar si se puede aplicar una dosis
     */
    private function puedeAplicarDosis($persona, $dosis, $esDependiente)
    {
        // Si ya está aplicada, no se puede aplicar de nuevo
        if ($this->buscarAplicacion($persona, $dosis, $esDependiente)) {
            return false;
        }

        // Calcular edad en meses directamente
        if ($persona->fecha_nacimiento) {
            $fechaNacimiento = Carbon::parse($persona->fecha_nacimiento);
            $edadMeses = intval($fechaNacimiento->diffInMonths(Carbon::now()));
        } else {
            $edadMeses = 0;
        }

        // Verificar si cumple la edad mínima
        if ($dosis->edad_aplicacion !== null && $edadMeses < $dosis->edad_aplicacion) {
            return false;
        }

        // Si requiere dosis anterior, verificar que esté aplicada
        if ($dosis->numero_dosis > 1) {
            $dosisAnterior = DosisVacuna::where('vacuna_id', $dosis->vacuna_id)
                ->where('numero_dosis', $dosis->numero_dosis - 1)
                ->first();

            if ($dosisAnterior && !$this->buscarAplicacion($persona, $dosisAnterior, $esDependiente)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calcular días restantes para aplicar una dosis
     */
    private function calcularDiasParaAplicacion($persona, $dosis, $esDependiente)
    {
        if ($dosis->edad_aplicacion === null) {
            return 0; // Se puede aplicar en cualquier momento
        }

        $fechaNacimiento = $esDependiente ? $persona->fecha_nacimiento : $persona->fecha_nacimiento;
        $fechaAplicacion = Carbon::parse($fechaNacimiento)->addMonths($dosis->edad_aplicacion);
        $diasRestantes = now()->diffInDays($fechaAplicacion, false);

        return $diasRestantes;
    }
}
