<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EsquemaVacunacion;
use App\Models\GrupoEdad;
use App\Models\Vacuna;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EsquemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EsquemaVacunacion::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('grupo_edad', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grupo_edad')) {
            $query->where('grupo_edad', $request->get('grupo_edad'));
        }

        if ($request->filled('obligatorio')) {
            $query->where('obligatorio', $request->get('obligatorio') === 'true');
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->get('activo') === 'true');
        }

        $esquemas = $query->orderBy('id', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        // Obtener grupos de edad únicos para el filtro
        $gruposEdad = EsquemaVacunacion::distinct()
                                     ->pluck('grupo_edad')
                                     ->filter()
                                     ->sort()
                                     ->values();

        return Inertia::render('Admin/Esquemas/Index', [
            'esquemas' => $esquemas,
            'grupos_edad' => $gruposEdad,
            'filters' => $request->only(['search', 'grupo_edad', 'obligatorio', 'activo'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener grupos de edad desde la base de datos
        $gruposEdadPredefinidos = GrupoEdad::getNombresActivos();
        $gruposEdadCompletos = GrupoEdad::getGruposParaFormulario();

        return Inertia::render('Admin/Esquemas/Create', [
            'grupos_edad_predefinidos' => $gruposEdadPredefinidos,
            'grupos_edad_completos' => $gruposEdadCompletos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:esquema_vacunacions,nombre'
            ],
            'descripcion' => 'nullable|string',
            'grupo_edad' => 'required|string|max:100',
            'edad_inicio' => 'nullable|integer|min:0',
            'edad_fin' => 'nullable|integer|min:0|gte:edad_inicio',
            'obligatorio' => 'boolean',
            'activo' => 'boolean'
        ], [
            'nombre.required' => 'El nombre del esquema es obligatorio.',
            'nombre.unique' => 'Ya existe un esquema con este nombre.',
            'grupo_edad.required' => 'El grupo de edad es obligatorio.',
            'edad_fin.gte' => 'La edad fin debe ser mayor o igual a la edad de inicio.'
        ]);

        EsquemaVacunacion::create($validated);

        return redirect()
            ->route('admin.esquemas.index')
            ->with('message', "El esquema de vacunación '{$validated['nombre']}' ha sido creado exitosamente.");
    }

    /**
     * Display the specified resource.
     */
    public function show(EsquemaVacunacion $esquema)
    {
        // Estadísticas del esquema
        $estadisticas = [
            'total_vacunas' => $esquema->dosisVacunas()->distinct('vacuna_id')->count(),
            'total_dosis' => $esquema->dosisVacunas()->count(),
            'es_obligatorio' => $esquema->obligatorio,
            'promedio_edad' => $esquema->edad_inicio && $esquema->edad_fin
                ? ($esquema->edad_inicio + $esquema->edad_fin) / 2
                : null
        ];

        return Inertia::render('Admin/Esquemas/Show', [
            'esquema' => $esquema,
            'estadisticas' => $estadisticas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EsquemaVacunacion $esquema)
    {
        // Obtener grupos de edad desde la base de datos
        $gruposEdadPredefinidos = GrupoEdad::getNombresActivos();
        $gruposEdadCompletos = GrupoEdad::getGruposParaFormulario();

        return Inertia::render('Admin/Esquemas/Edit', [
            'esquema' => $esquema,
            'grupos_edad_predefinidos' => $gruposEdadPredefinidos,
            'grupos_edad_completos' => $gruposEdadCompletos
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EsquemaVacunacion $esquema)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('esquema_vacunacions', 'nombre')->ignore($esquema->id)
            ],
            'descripcion' => 'nullable|string',
            'grupo_edad' => 'required|string|max:100',
            'edad_inicio' => 'nullable|integer|min:0',
            'edad_fin' => 'nullable|integer|min:0|gte:edad_inicio',
            'obligatorio' => 'boolean',
            'activo' => 'boolean'
        ], [
            'nombre.required' => 'El nombre del esquema es obligatorio.',
            'nombre.unique' => 'Ya existe un esquema con este nombre.',
            'grupo_edad.required' => 'El grupo de edad es obligatorio.',
            'edad_fin.gte' => 'La edad fin debe ser mayor o igual a la edad de inicio.'
        ]);

        $esquema->update($validated);

        return redirect()
            ->route('admin.esquemas.index')
            ->with('message', "El esquema de vacunación '{$validated['nombre']}' ha sido actualizado exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EsquemaVacunacion $esquema)
    {
        // Verificar si el esquema tiene dosis de vacunas registradas
        if ($esquema->dosisVacunas()->exists()) {
            return redirect()
                ->route('admin.esquemas.index')
                ->with('error', 'No se puede eliminar el esquema porque tiene vacunas asociadas.');
        }

        $nombre = $esquema->nombre;
        $esquema->delete();

        return redirect()
            ->route('admin.esquemas.index')
            ->with('message', "El esquema de vacunación '{$nombre}' ha sido eliminado exitosamente.");
    }

    /**
     * Mostrar vista para gestionar vacunas del esquema
     */
    public function manageVacunas(EsquemaVacunacion $esquema)
    {
        $esquema->load(['dosisVacunas.vacuna']);

        // Obtener las vacunas del esquema agrupadas por dosis
        $vacunas = $esquema->dosisVacunas()
            ->with(['vacuna'])
            ->orderBy('numero_dosis')
            ->orderBy('edad_aplicacion')
            ->get()
            ->groupBy('vacuna.nombre');

        // Obtener vacunas disponibles para agregar
        $vacunasDisponibles = Vacuna::where('activa', true)
                                    ->select('id', 'nombre', 'tipo', 'dosis_requeridas')
                                    ->orderBy('nombre')
                                    ->get();

        return Inertia::render('Admin/Esquemas/ManageVacunas', [
            'esquema' => $esquema,
            'vacunas' => $vacunas,
            'vacunasDisponibles' => $vacunasDisponibles
        ]);
    }

    /**
     * Obtener vacunas disponibles para asociar al esquema
     */
    public function getVacunasDisponibles()
    {
        $vacunas = Vacuna::where('activa', true)
                         ->select('id', 'nombre', 'tipo', 'dosis_requeridas')
                         ->orderBy('nombre')
                         ->get();

        return response()->json($vacunas);
    }

    /**
     * Agregar una dosis de vacuna al esquema
     */
    public function storeDosisVacuna(Request $request, EsquemaVacunacion $esquema)
    {
        // Debug: Log datos recibidos
        Log::info('Datos recibidos para dosis vacuna:', $request->all());

        $request->validate([
            'vacuna_id' => 'required|exists:vacunas,id',
            'numero_dosis' => 'required|integer|min:1',
            'edad_aplicacion' => 'nullable|integer|min:0',
            'dias_despues_anterior' => 'nullable|integer|min:0',
            'es_refuerzo' => 'boolean',
            'es_obligatoria' => 'boolean',
            'activo' => 'boolean',
            'observaciones' => 'nullable|string|max:500',
            'justificacion_medica' => 'nullable|string|max:1000'
        ]);

        // Verificar si ya existe esta combinación
        $existe = $esquema->dosisVacunas()
                          ->where('vacuna_id', $request->vacuna_id)
                          ->where('numero_dosis', $request->numero_dosis)
                          ->exists();

        if ($existe) {
            return back()->withErrors([
                'numero_dosis' => 'Ya existe esta dosis para la vacuna seleccionada en este esquema.'
            ]);
        }

        $datosParaCrear = [
            'vacuna_id' => $request->vacuna_id,
            'numero_dosis' => $request->numero_dosis,
            'edad_aplicacion' => $request->edad_aplicacion,
            'dias_despues_anterior' => $request->dias_despues_anterior,
            'es_refuerzo' => $request->boolean('es_refuerzo'),
            'es_obligatoria' => $request->boolean('es_obligatoria', true), // Default true si no se especifica
            'activo' => $request->boolean('activo', true), // Default true si no se especifica
            'observaciones' => $request->observaciones,
            'justificacion_medica' => $request->justificacion_medica
        ];

        // Debug: Log datos que se van a crear
        Log::info('Datos para crear dosis:', $datosParaCrear);

        $dosis = $esquema->dosisVacunas()->create($datosParaCrear);

        // Debug: Log datos creados
        Log::info('Dosis creada:', $dosis->toArray());

        return back()->with('message', 'Dosis de vacuna agregada exitosamente.');
    }

    /**
     * Actualizar una dosis de vacuna del esquema
     */
    public function updateDosisVacuna(Request $request, EsquemaVacunacion $esquema, $dosisId)
    {
        $dosis = $esquema->dosisVacunas()->findOrFail($dosisId);

        $validated = $request->validate([
            'vacuna_id' => 'required|exists:vacunas,id',
            'numero_dosis' => 'required|integer|min:1',
            'edad_aplicacion' => 'nullable|integer|min:0',
            'dias_despues_anterior' => 'nullable|integer|min:0',
            'es_refuerzo' => 'boolean',
            'es_obligatoria' => 'boolean',
            'activo' => 'boolean',
            'observaciones' => 'nullable|string|max:500',
            'justificacion_medica' => 'nullable|string|max:1000'
        ]);

        // Verificar si ya existe esta combinación (excluyendo la dosis actual)
        $existe = $esquema->dosisVacunas()
                          ->where('vacuna_id', $request->vacuna_id)
                          ->where('numero_dosis', $request->numero_dosis)
                          ->where('id', '!=', $dosisId)
                          ->exists();

        if ($existe) {
            return back()->withErrors([
                'numero_dosis' => 'Ya existe esta dosis para la vacuna seleccionada en este esquema.'
            ]);
        }

        // Debug: Log datos que se van a actualizar
        Log::info('Actualizando dosis ID: ' . $dosisId, $validated);

        $dosis->update($validated);

        // Debug: Log datos actualizados
        Log::info('Dosis actualizada:', $dosis->fresh()->toArray());

        return back()->with('message', 'Dosis de vacuna actualizada exitosamente.');
    }

    /**
     * Eliminar una dosis de vacuna del esquema
     */
    public function destroyDosisVacuna(EsquemaVacunacion $esquema, $dosisId)
    {
        $dosis = $esquema->dosisVacunas()->findOrFail($dosisId);

        $dosis->delete();

        return back()->with('message', 'Dosis de vacuna eliminada exitosamente.');
    }
}
