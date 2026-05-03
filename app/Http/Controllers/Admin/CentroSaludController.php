<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentroSalud;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class CentroSaludController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CentroSalud::with(['distrito.provincia.departamento']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('codigo', 'like', '%' . $request->search . '%')
                  ->orWhere('director_medico', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        if ($request->filled('departamento')) {
            $query->whereHas('distrito.provincia.departamento', function ($q) use ($request) {
                $q->where('codigo_ubigeo', $request->departamento);
            });
        }

        if ($request->filled('provincia')) {
            $query->whereHas('distrito.provincia', function ($q) use ($request) {
                $q->where('codigo_ubigeo', $request->provincia);
            });
        }

        if ($request->filled('distrito')) {
            $query->where('distrito_codigo', $request->distrito);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        $centrosSalud = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Datos para filtros
        $departamentos = Departamento::orderBy('nombre')->get();
        $provincias = collect();
        $distritos = collect();

        if ($request->filled('departamento')) {
            $provincias = Provincia::where('departamento_codigo', $request->departamento)
                ->orderBy('nombre')->get();
        }

        if ($request->filled('provincia')) {
            $distritos = Distrito::where('provincia_codigo', $request->provincia)
                ->orderBy('nombre')->get();
        }

        return Inertia::render('Admin/CentrosSalud/Index', [
            'centrosSalud' => $centrosSalud,
            'filters' => $request->only(['search', 'tipo', 'departamento', 'provincia', 'distrito', 'activo']),
            'departamentos' => $departamentos,
            'provincias' => $provincias,
            'distritos' => $distritos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();

        return Inertia::render('Admin/CentrosSalud/Create', [
            'departamentos' => $departamentos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:centros_salud',
            'tipo' => 'required|in:Hospital,Centro de Salud,Puesto de Salud,Clínica',
            'nivel' => 'required|in:I-1,I-2,I-3,I-4,II-1,II-2,II-E,III-1,III-2,III-E',
            'direccion' => 'required|string|max:500',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'distrito_codigo' => 'required|exists:distritos,codigo_ubigeo',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'capacidad_atencion' => 'nullable|integer|min:0',
            'horario_inicio' => 'nullable|date_format:H:i',
            'horario_fin' => 'nullable|date_format:H:i',
            'servicios_disponibles' => 'nullable|array',
            'servicios_disponibles.*' => 'string',
            'director_medico' => 'nullable|string|max:255',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mapear los datos del formulario a los campos de la base de datos
        $data = $request->all();

        // Mapear servicios_disponibles a servicios
        if (isset($data['servicios_disponibles'])) {
            $data['servicios'] = $data['servicios_disponibles'];
            unset($data['servicios_disponibles']);
        }

        // Mapear capacidad_atencion a capacidad
        if (isset($data['capacidad_atencion'])) {
            $data['capacidad'] = $data['capacidad_atencion'];
            unset($data['capacidad_atencion']);
        }

        // Mapear nivel a nivel_atencion
        if (isset($data['nivel'])) {
            $data['nivel_atencion'] = $data['nivel'];
            unset($data['nivel']);
        }

        // Manejar campos que no pueden ser NULL pero pueden venir vacíos del formulario
        if (empty($data['capacidad']) || $data['capacidad'] === '') {
            $data['capacidad'] = 0;
        }

        // Manejar horarios con valores por defecto
        if (empty($data['horario_inicio'])) {
            $data['horario_inicio'] = '08:00:00';
        }
        if (empty($data['horario_fin'])) {
            $data['horario_fin'] = '18:00:00';
        }

        // Convertir strings vacías a null para campos opcionales
        if (empty($data['telefono'])) {
            $data['telefono'] = null;
        }
        if (empty($data['email'])) {
            $data['email'] = null;
        }
        if (empty($data['latitud']) || $data['latitud'] === '') {
            $data['latitud'] = null;
        }
        if (empty($data['longitud']) || $data['longitud'] === '') {
            $data['longitud'] = null;
        }
        if (empty($data['director_medico'])) {
            $data['director_medico'] = null;
        }

        CentroSalud::create($data);

        return redirect()->route('admin.centros-salud.index')
            ->with('success', 'Centro de salud creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroSalud $centros_salud)
    {
        $centros_salud->load(['distrito.provincia.departamento', 'citas', 'aplicaciones']);

        return Inertia::render('Admin/CentrosSalud/Show', [
            'centroSalud' => $centros_salud,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroSalud $centros_salud)
    {
        // Cargar relaciones de forma segura
        try {
            $centros_salud->load(['distrito.provincia.departamento']);
        } catch (\Exception $e) {
            // Si hay error cargando las relaciones, cargar sin ellas
            $centros_salud->load(['distrito']);
        }

        $departamentos = Departamento::orderBy('nombre')->get();

        $provincias = collect();
        $distritos = collect();

        // Verificar que existe el distrito y la provincia de forma segura
        if ($centros_salud->distrito) {
            // Cargar provincia si no está cargada
            if (!$centros_salud->distrito->relationLoaded('provincia')) {
                $centros_salud->distrito->load('provincia.departamento');
            }

            if ($centros_salud->distrito->provincia) {
                $provincia = $centros_salud->distrito->provincia;
                $provincias = Provincia::where('departamento_codigo', $provincia->departamento_codigo)
                    ->orderBy('nombre')->get();

                $distritos = Distrito::where('provincia_codigo', $provincia->codigo_ubigeo)
                    ->orderBy('nombre')->get();
            }
        }

        return Inertia::render('Admin/CentrosSalud/Edit', [
            'centroSalud' => $centros_salud,
            'departamentos' => $departamentos,
            'provincias' => $provincias,
            'distritos' => $distritos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroSalud $centros_salud)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:centros_salud,codigo,' . $centros_salud->id,
            'tipo' => 'required|in:Hospital,Centro de Salud,Puesto de Salud,Clínica',
            'nivel_atencion' => 'required|in:I-1,I-2,I-3,I-4,II-1,II-2,II-E,III-1,III-2,III-E',
            'direccion' => 'required|string|max:500',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'distrito_codigo' => 'required|exists:distritos,codigo_ubigeo',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'capacidad' => 'nullable|integer|min:0',
            'horario_inicio' => 'nullable|date_format:H:i',
            'horario_fin' => 'nullable|date_format:H:i',
            'servicios' => 'nullable|array',
            'servicios.*' => 'string',
            'director_medico' => 'nullable|string|max:255',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Procesar los datos antes de actualizar
        $data = $request->all();

        // Manejar campos que no pueden ser NULL pero pueden venir vacíos del formulario
        if (empty($data['capacidad']) || $data['capacidad'] === '') {
            $data['capacidad'] = 0;
        }

        // Manejar horarios con valores por defecto
        if (empty($data['horario_inicio'])) {
            $data['horario_inicio'] = '08:00:00';
        }
        if (empty($data['horario_fin'])) {
            $data['horario_fin'] = '18:00:00';
        }

        // Convertir strings vacías a null para campos opcionales
        if (empty($data['telefono'])) {
            $data['telefono'] = null;
        }
        if (empty($data['email'])) {
            $data['email'] = null;
        }
        if (empty($data['latitud']) || $data['latitud'] === '') {
            $data['latitud'] = null;
        }
        if (empty($data['longitud']) || $data['longitud'] === '') {
            $data['longitud'] = null;
        }
        if (empty($data['director_medico'])) {
            $data['director_medico'] = null;
        }

        $centros_salud->update($data);

        return redirect()->route('admin.centros-salud.index')
            ->with('success', 'Centro de salud actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroSalud $centros_salud)
    {
        $centros_salud->delete();

        return redirect()->route('admin.centros-salud.index')
            ->with('success', 'Centro de salud eliminado exitosamente.');
    }

    /**
     * Get provincias by departamento
     */
    public function getProvincias($departamentoCodigo)
    {
        $provincias = Provincia::where('departamento_codigo', $departamentoCodigo)
            ->orderBy('nombre')
            ->get();

        return response()->json($provincias);
    }

    /**
     * Get distritos by provincia
     */
    public function getDistritos($provinciaCodigo)
    {
        $distritos = Distrito::where('provincia_codigo', $provinciaCodigo)
            ->orderBy('nombre')
            ->get();

        return response()->json($distritos);
    }

    /**
     * Toggle status of centro de salud
     */
    public function toggleStatus(CentroSalud $centros_salud)
    {
        $centros_salud->update([
            'activo' => !$centros_salud->activo
        ]);

        $status = $centros_salud->activo ? 'activado' : 'desactivado';

        return redirect()->back()->with('success', "Centro de salud {$status} exitosamente.");
    }
}
