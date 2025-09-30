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
            // Excluir aplicaciones que fueron registradas para dependientes (tienen dependiente_id)
            $aplicacionesHistorico = AplicacionVacuna::where('user_id', $persona->id)
                ->whereNull('dependiente_id')
                ->with(['vacuna', 'centroSalud'])
                ->orderBy('fecha_aplicacion', 'desc')
                ->get();
        } else {
            // Para dependientes, usar el ID del usuario registrado o del tutor
            $pacienteId = $persona->dependiente_user_id ?? null;
            $query = AplicacionVacuna::with(['vacuna', 'centroSalud'])->orderBy('fecha_aplicacion', 'desc');

            if ($pacienteId) {
                // Incluir aplicaciones del usuario vinculado y las que tengan dependiente_id
                $query->where(function($q) use ($pacienteId, $persona) {
                    $q->where('user_id', $pacienteId)
                      ->orWhere('dependiente_id', $persona->id);
                });
            } else {
                // No hay usuario vinculado: buscar por dependiente_id
                $query->where('dependiente_id', $persona->id);
            }

            $aplicacionesHistorico = $query->get();
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
            'persona_id' => 'required_if:persona_tipo,dependiente|nullable|integer',
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
        $existeQuery = AplicacionVacuna::where('user_id', $pacienteId)
            ->where('vacuna_id', $dosisVacuna->vacuna_id)
            ->where('numero_dosis', $dosisVacuna->numero_dosis);

        // Si estamos marcando para el usuario principal, ignorar aplicaciones que pertenecen a dependientes
        if ($request->persona_tipo === 'usuario') {
            $existeQuery->whereNull('dependiente_id');
        }

        $existeAplicacion = $existeQuery->exists();

        if ($existeAplicacion) {
            return back()->with('error', 'Esta dosis ya está marcada como aplicada.');
        }

        // Crear la aplicación
        // Preparar datos básicos
        $dataToCreate = [
            'user_id' => $pacienteId,
            'vacuna_id' => $dosisVacuna->vacuna_id,
            'dosis_vacuna_id' => $dosisVacuna->id,
            'medico_id' => null,
            'centro_salud_id' => $request->centro_salud_id,
            'fecha_aplicacion' => $request->fecha_aplicacion,
            'numero_dosis' => $dosisVacuna->numero_dosis,
            'lote_vacuna' => null,
            'fecha_vencimiento' => null,
            'observaciones' => $request->observaciones,
            'reaccion_adversa' => null,
            'proxima_dosis' => null
        ];

        // Si se marcó desde vista de dependiente, guardar su id también
        if ($request->persona_tipo === 'dependiente' && $request->persona_id) {
            $dataToCreate['dependiente_id'] = $request->persona_id;
        }

        AplicacionVacuna::create($dataToCreate);

        return back()->with('message', 'Dosis marcada como aplicada exitosamente.');
    }

    /**
     * Obtener centros de salud activos para el selector
     */
    public function getCentrosSalud()
    {
        try {
            // Asegurarnos de seleccionar columnas existentes. En la tabla el campo es 'distrito_codigo'
            $centros = CentroSalud::activos()
                ->select('id', 'nombre', 'direccion', 'distrito_codigo')
                ->orderBy('nombre')
                ->get();

            return response()->json($centros);
        } catch (\Exception $e) {
            // Loguear el error y devolver un JSON con status 500 para que el frontend lo maneje
            Log::error('Error obteniendo centros de salud: ' . $e->getMessage());
            return response()->json(['error' => 'Error cargando centros de salud'], 500);
        }
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

                // Calcular fecha estimada y origen
                $calc = $this->calcularDiasParaAplicacion($persona, $dosis, $esDependiente);

                return [
                    'dosis' => $dosis,
                    'aplicada' => $aplicacion !== null,
                    'aplicacion' => $aplicacion,
                    'puede_aplicar' => $this->puedeAplicarDosis($persona, $dosis, $esDependiente),
                    'dias_para_aplicacion' => $calc['dias'],
                    'fecha_estimada_aplicacion' => $calc['fecha'],
                    'fuente_calculo' => $calc['fuente'],
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
            // Si el dependiente tiene user vinculado, buscar por ese user_id
            if ($persona->dependiente_user_id) {
                return AplicacionVacuna::where(function($q) use ($persona) {
                        $q->where('user_id', $persona->dependiente_user_id)
                          ->orWhere('dependiente_id', $persona->id);
                    })
                    ->where('vacuna_id', $dosis->vacuna_id)
                    ->where('numero_dosis', $dosis->numero_dosis)
                    ->first();
            }

            // Si no tiene user vinculado, buscar por dependiente_id en la tabla de aplicaciones
            $byDependiente = AplicacionVacuna::where('dependiente_id', $persona->id)
                ->where('vacuna_id', $dosis->vacuna_id)
                ->where('numero_dosis', $dosis->numero_dosis)
                ->first();

            if ($byDependiente) return $byDependiente;

            // Fallback: buscar por número de documento relacionado con paciente (caso legacy)
            return AplicacionVacuna::whereHas('paciente', function($query) use ($persona) {
                    $query->where('numero_documento', $persona->numero_documento);
                })
                ->where('vacuna_id', $dosis->vacuna_id)
                ->where('numero_dosis', $dosis->numero_dosis)
                ->first();
        }

        // Para el usuario principal
        // Cuando se consulta el usuario principal, ignorar aplicaciones que pertenezcan a dependientes
        return AplicacionVacuna::where('user_id', $persona->id)
            ->whereNull('dependiente_id')
            ->where('vacuna_id', $dosis->vacuna_id)
            ->where('numero_dosis', $dosis->numero_dosis)
            ->first();
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
        // Valor por defecto
        $result = [
            'dias' => 0,
            'fecha' => null,
            'fuente' => 'unknown'
        ];

        // Si la dosis no tiene edad mínima definida, se puede aplicar en cualquier momento
        if ($dosis->edad_aplicacion === null) {
            $result['dias'] = 0;
            $result['fecha'] = now()->toDateString();
            $result['fuente'] = 'inmediato';
            return $result;
        }

        // Intentar calcular por intervalo relativo a aplicación previa si corresponde
        try {
            if ($dosis->numero_dosis > 1 && !is_null($dosis->dias_despues_anterior)) {
                // Buscar la definición de la dosis anterior
                $dosisAnterior = DosisVacuna::where('vacuna_id', $dosis->vacuna_id)
                    ->where('numero_dosis', $dosis->numero_dosis - 1)
                    ->first();

                if ($dosisAnterior) {
                    // Buscar la aplicación previa (si existe) para la persona según tipo
                    $aplicacionPrev = $this->buscarAplicacion($persona, $dosisAnterior, $esDependiente);
                    if ($aplicacionPrev && $aplicacionPrev->fecha_aplicacion) {
                        $fechaPrev = Carbon::parse($aplicacionPrev->fecha_aplicacion);

                        // Calcular la fecha objetivo original de la dosis anterior basada en edad_aplicacion
                        $usarIntervaloDesdePrev = false;
                        if (!is_null($dosisAnterior->edad_aplicacion) && $persona->fecha_nacimiento) {
                            $fechaNacimiento = Carbon::parse($persona->fecha_nacimiento);
                            $fechaObjetivoAnteriorPorEdad = $fechaNacimiento->copy()->addMonths($dosisAnterior->edad_aplicacion);

                            // Usar intervalo sólo si la aplicación previa fue estrictamente posterior a su fecha objetivo
                            if ($fechaPrev->greaterThan($fechaObjetivoAnteriorPorEdad)) {
                                $usarIntervaloDesdePrev = true;
                            }
                        }

                        if ($usarIntervaloDesdePrev) {
                            $fechaObjetivo = $fechaPrev->copy()->addDays(intval($dosis->dias_despues_anterior));
                            $result['fecha'] = $fechaObjetivo->toDateString();
                            $result['dias'] = now()->diffInDays($fechaObjetivo, false);
                            $result['fuente'] = 'intervalo_prev';
                            return $result;
                        }
                        // Si la aplicación previa fue anterior o en fecha, no usar el intervalo
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Error calculando fecha por dias_despues_anterior: ' . $e->getMessage());
            // continuar con fallback
        }

        // Fallback: calcular por edad mínima (edad_aplicacion en meses)
        $fechaNacimiento = $persona->fecha_nacimiento;
        if (!$fechaNacimiento) {
            // Si no hay fecha de nacimiento, no podemos estimar; devolver inmediato
            $result['dias'] = 0;
            $result['fecha'] = now()->toDateString();
            $result['fuente'] = 'unknown';
            return $result;
        }

        $fechaAplicacion = Carbon::parse($fechaNacimiento)->addMonths($dosis->edad_aplicacion);
        $diasRestantes = now()->diffInDays($fechaAplicacion, false);

        $result['dias'] = $diasRestantes;
        $result['fecha'] = $fechaAplicacion->toDateString();
        $result['fuente'] = 'edad';

        return $result;
    }
}
