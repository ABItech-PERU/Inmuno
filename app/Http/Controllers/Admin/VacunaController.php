<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacuna;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VacunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vacuna::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('fabricante', 'like', "%{$search}%")
                  ->orWhere('tipo', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->get('tipo'));
        }

        if ($request->filled('activa')) {
            $query->where('activa', $request->get('activa') === 'true');
        }

        $vacunas = $query->orderBy('id', 'desc')
                        ->paginate(15)
                        ->withQueryString();

        // Obtener tipos únicos para el filtro
        $tipos = Vacuna::distinct()
                      ->pluck('tipo')
                      ->filter()
                      ->sort()
                      ->values();

        return Inertia::render('Admin/Vacunas/Index', [
            'vacunas' => $vacunas,
            'tipos' => $tipos,
            'filters' => $request->only(['search', 'tipo', 'activa'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Vacunas/Create');
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
                'unique:vacunas,nombre'
            ],
            'descripcion' => 'nullable|string',
            'fabricante' => 'nullable|string|max:255',
            'tipo' => 'required|string|max:255',
            'dosis_requeridas' => 'required|integer|min:1|max:10',
            'intervalo_entre_dosis' => 'nullable|integer|min:1',
            'edad_minima' => 'nullable|integer|min:0',
            'edad_maxima' => 'nullable|integer|min:0|gte:edad_minima',
            'contraindicaciones' => 'nullable|string',
            'efectos_secundarios' => 'nullable|string',
            'activa' => 'boolean'
        ], [
            'nombre.required' => 'El nombre de la vacuna es obligatorio.',
            'nombre.unique' => 'Ya existe una vacuna con este nombre.',
            'tipo.required' => 'El tipo de vacuna es obligatorio.',
            'dosis_requeridas.required' => 'El número de dosis requeridas es obligatorio.',
            'dosis_requeridas.min' => 'Debe requerir al menos 1 dosis.',
            'dosis_requeridas.max' => 'No puede requerir más de 10 dosis.',
            'edad_maxima.gte' => 'La edad máxima debe ser mayor o igual a la edad mínima.'
        ]);

        Vacuna::create($validated);

        return redirect()
            ->route('admin.vacunas.index')
            ->with('message', "La vacuna '{$validated['nombre']}' ha sido creada exitosamente.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Vacuna $vacuna)
    {
        $vacuna->load(['dosisVacunas.esquemaVacunacion', 'aplicaciones']);

        // Estadísticas de la vacuna
        $estadisticas = [
            'total_aplicaciones' => $vacuna->aplicaciones()->count(),
            'aplicaciones_ultimo_mes' => $vacuna->aplicaciones()
                ->where('fecha_aplicacion', '>=', now()->subMonth())
                ->count(),
            'esquemas_incluidos' => $vacuna->dosisVacunas()
                ->distinct('esquema_vacunacion_id')
                ->count()
        ];

        return Inertia::render('Admin/Vacunas/Show', [
            'vacuna' => $vacuna,
            'estadisticas' => $estadisticas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacuna $vacuna)
    {
        return Inertia::render('Admin/Vacunas/Edit', [
            'vacuna' => $vacuna
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacuna $vacuna)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vacunas', 'nombre')->ignore($vacuna->id)
            ],
            'descripcion' => 'nullable|string',
            'fabricante' => 'nullable|string|max:255',
            'tipo' => 'required|string|max:255',
            'dosis_requeridas' => 'required|integer|min:1|max:10',
            'intervalo_entre_dosis' => 'nullable|integer|min:1',
            'edad_minima' => 'nullable|integer|min:0',
            'edad_maxima' => 'nullable|integer|min:0|gte:edad_minima',
            'contraindicaciones' => 'nullable|string',
            'efectos_secundarios' => 'nullable|string',
            'activa' => 'boolean'
        ], [
            'nombre.required' => 'El nombre de la vacuna es obligatorio.',
            'nombre.unique' => 'Ya existe una vacuna con este nombre.',
            'tipo.required' => 'El tipo de vacuna es obligatorio.',
            'dosis_requeridas.required' => 'El número de dosis requeridas es obligatorio.',
            'dosis_requeridas.min' => 'Debe requerir al menos 1 dosis.',
            'dosis_requeridas.max' => 'No puede requerir más de 10 dosis.',
            'edad_maxima.gte' => 'La edad máxima debe ser mayor o igual a la edad mínima.'
        ]);

        $vacuna->update($validated);

        return redirect()
            ->route('admin.vacunas.index')
            ->with('message', "La vacuna '{$validated['nombre']}' ha sido actualizada exitosamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacuna $vacuna)
    {
        // Verificar si la vacuna tiene aplicaciones registradas
        if ($vacuna->aplicaciones()->exists()) {
            return redirect()
                ->route('admin.vacunas.index')
                ->with('error', 'No se puede eliminar la vacuna porque tiene aplicaciones registradas.');
        }

        $nombre = $vacuna->nombre;
        $vacuna->delete();

        return redirect()
            ->route('admin.vacunas.index')
            ->with('message', "La vacuna '{$nombre}' ha sido eliminada exitosamente.");
    }

    /**
     * Cambiar estado activo/inactivo de la vacuna
     */
    public function toggleStatus(Vacuna $vacuna)
    {
        $vacuna->update(['activa' => !$vacuna->activa]);

        $estado = $vacuna->activa ? 'activada' : 'desactivada';

        return redirect()
            ->route('admin.vacunas.index')
            ->with('message', "La vacuna '{$vacuna->nombre}' ha sido {$estado} exitosamente.");
    }
}
