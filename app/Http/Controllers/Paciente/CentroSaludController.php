<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\CentroSalud;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CentroSaludController extends Controller
{
    public function index(Request $request)
    {
        // Vista Inertia: la página cargará filtros y mapa, luego consultará la API list
        return Inertia::render('Paciente/CentroSaluds/Index');
    }

    /**
     * Devuelve JSON con centros filtrados por departamento/provincia/distrito y busqueda por nombre
     */
    public function list(Request $request)
    {
        $q = CentroSalud::query()->select([
            'id', 'nombre', 'codigo', 'tipo', 'direccion', 'telefono', 'email', 'distrito_codigo', 'latitud', 'longitud', 'nivel_atencion', 'activo'
        ]);

        if ($request->filled('departamento')) {
            $q->whereHas('distrito.provincia', function ($q2) use ($request) {
                $q2->where('departamento_codigo', $request->input('departamento'));
            });
        }

        if ($request->filled('provincia')) {
            $q->whereHas('distrito', function ($q2) use ($request) {
                $q2->where('provincia_codigo', $request->input('provincia'));
            });
        }

        if ($request->filled('distrito')) {
            $q->where('distrito_codigo', $request->input('distrito'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $q->where('nombre', 'like', "%{$search}%");
        }

        // solo activos por defecto
        if (! $request->has('activo') || $request->input('activo')) {
            $q->where('activo', true);
        }

        $centros = $q->limit(1000)->with(['distrito.provincia'])->get();

        // Mapear para incluir nombres legibles de distrito y provincia
        $data = $centros->map(function ($c) {
            return [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'codigo' => $c->codigo,
                'tipo' => $c->tipo,
                'direccion' => $c->direccion,
                'telefono' => $c->telefono,
                'email' => $c->email,
                'distrito_codigo' => $c->distrito_codigo,
                'distrito_nombre' => $c->distrito->nombre ?? null,
                'provincia_nombre' => $c->distrito->provincia->nombre ?? null,
                'latitud' => $c->latitud,
                'longitud' => $c->longitud,
                'nivel_atencion' => $c->nivel_atencion,
                'activo' => $c->activo,
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Devuelve lista de departamentos (público)
     */
    public function getDepartamentos()
    {
        $deps = Departamento::activos()->orderBy('nombre')->get(['codigo_ubigeo as codigo', 'nombre']);
        return response()->json($deps);
    }

    /**
     * Devuelve lista de provincias para un departamento (público)
     */
    public function getProvinciasPublic($departamento)
    {
        $provs = Provincia::where('departamento_codigo', $departamento)->activos()->orderBy('nombre')->get(['codigo_ubigeo as codigo', 'nombre']);
        return response()->json($provs);
    }

    /**
     * Devuelve lista de distritos para una provincia (público)
     */
    public function getDistritosPublic($provincia)
    {
        $d = Distrito::where('provincia_codigo', $provincia)->activos()->orderBy('nombre')->get(['codigo_ubigeo', 'nombre']);
        return response()->json($d);
    }
}
