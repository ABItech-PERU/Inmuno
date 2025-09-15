<?php

namespace App\Http\Controllers\Paciente;

use App\Http\Controllers\Controller;
use App\Models\Dependiente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DependientesController extends Controller
{
    /**
     * Mostrar lista de dependientes del usuario autenticado
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Obtener filtros
        $filtroParentesco = $request->get('parentesco');
        $filtroEstado = $request->get('estado', 'todos');
        $busqueda = $request->get('busqueda');

        // Query base para dependientes
        $query = Dependiente::where('tutor_id', $user->id);

        // Aplicar filtros
        if ($filtroParentesco && $filtroParentesco !== 'todos') {
            $query->where('parentesco', $filtroParentesco);
        }

        if ($filtroEstado === 'activos') {
            $query->where('activo', true);
        } elseif ($filtroEstado === 'inactivos') {
            $query->where('activo', false);
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombres', 'LIKE', "%{$busqueda}%")
                  ->orWhere('apellidos', 'LIKE', "%{$busqueda}%")
                  ->orWhere('numero_documento', 'LIKE', "%{$busqueda}%");
            });
        }

        $dependientes = $query->orderBy('nombres')
            ->paginate(10)
            ->withQueryString();

        // Estadísticas
        $estadisticas = [
            'total_dependientes' => Dependiente::where('tutor_id', $user->id)->count(),
            'activos' => Dependiente::where('tutor_id', $user->id)->where('activo', true)->count(),
            'inactivos' => Dependiente::where('tutor_id', $user->id)->where('activo', false)->count(),
            'menores' => Dependiente::where('tutor_id', $user->id)->whereRaw('DATEDIFF(NOW(), fecha_nacimiento) < (18 * 365)')->count(),
        ];

        // Opciones de parentesco
        $parentescos = [
            'hijo/a' => 'Hijo/a',
            'nieto/a' => 'Nieto/a',
            'sobrino/a' => 'Sobrino/a',
            'hermano/a' => 'Hermano/a',
            'padre/madre' => 'Padre/Madre',
            'abuelo/a' => 'Abuelo/a',
            'otro' => 'Otro'
        ];

        return Inertia::render('Paciente/Dependientes/Index', [
            'dependientes' => $dependientes,
            'estadisticas' => $estadisticas,
            'parentescos' => $parentescos,
            'filtros' => [
                'parentesco' => $filtroParentesco,
                'estado' => $filtroEstado,
                'busqueda' => $busqueda,
            ]
        ]);
    }

    /**
     * Mostrar formulario para crear nuevo dependiente
     */
    public function create()
    {
        $parentescos = [
            'hijo/a' => 'Hijo/a',
            'nieto/a' => 'Nieto/a',
            'sobrino/a' => 'Sobrino/a',
            'hermano/a' => 'Hermano/a',
            'padre/madre' => 'Padre/Madre',
            'abuelo/a' => 'Abuelo/a',
            'otro' => 'Otro'
        ];

        $tiposDocumento = [
            'DNI' => 'DNI',
            'CE' => 'Carnet de Extranjería',
            'PASAPORTE' => 'Pasaporte'
        ];

        return Inertia::render('Paciente/Dependientes/Create', [
            'parentescos' => $parentescos,
            'tiposDocumento' => $tiposDocumento
        ]);
    }

    /**
     * Almacenar nuevo dependiente
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => ['required', Rule::in(['DNI', 'CE', 'PASAPORTE'])],
            'numero_documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dependientes')->where('tutor_id', $user->id)
            ],
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => ['required', Rule::in(['M', 'F'])],
            'parentesco' => ['required', Rule::in(['hijo/a', 'nieto/a', 'sobrino/a', 'hermano/a', 'padre/madre', 'abuelo/a', 'otro'])],
            'telefono' => 'nullable|string|max:15',
            'observaciones' => 'nullable|string|max:500'
        ], [
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.unique' => 'Ya tienes un dependiente registrado con este número de documento.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'genero.required' => 'El género es obligatorio.',
            'parentesco.required' => 'El parentesco es obligatorio.'
        ]);

        Dependiente::create([
            'tutor_id' => $user->id,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'parentesco' => $request->parentesco,
            'telefono' => $request->telefono,
            'observaciones' => $request->observaciones,
            'activo' => true
        ]);

        return redirect()->route('paciente.dependientes.index')
            ->with('success', 'Dependiente registrado exitosamente.');
    }

    /**
     * Mostrar detalles del dependiente
     */
    public function show(Dependiente $dependiente)
    {
        $user = Auth::user();

        // Verificar que el dependiente pertenece al usuario autenticado
        if ($dependiente->tutor_id !== $user->id) {
            abort(403, 'No tienes permisos para ver este dependiente.');
        }

        $dependiente->load(['tutor', 'usuarioRegistrado']);

        // Calcular estadísticas adicionales
        $estadisticas = [
            'edad_años' => $dependiente->edad,
            'edad_meses' => $dependiente->edad_en_meses,
            'es_menor' => $dependiente->esMenorDeEdad(),
            'tiene_usuario' => !is_null($dependiente->dependiente_user_id)
        ];

        return Inertia::render('Paciente/Dependientes/Show', [
            'dependiente' => $dependiente,
            'estadisticas' => $estadisticas
        ]);
    }

    /**
     * Mostrar formulario para editar dependiente
     */
    public function edit(Dependiente $dependiente)
    {
        $user = Auth::user();

        // Verificar que el dependiente pertenece al usuario autenticado
        if ($dependiente->tutor_id !== $user->id) {
            abort(403, 'No tienes permisos para editar este dependiente.');
        }

        $parentescos = [
            'hijo/a' => 'Hijo/a',
            'nieto/a' => 'Nieto/a',
            'sobrino/a' => 'Sobrino/a',
            'hermano/a' => 'Hermano/a',
            'padre/madre' => 'Padre/Madre',
            'abuelo/a' => 'Abuelo/a',
            'otro' => 'Otro'
        ];

        $tiposDocumento = [
            'DNI' => 'DNI',
            'CE' => 'Carnet de Extranjería',
            'PASAPORTE' => 'Pasaporte'
        ];

        return Inertia::render('Paciente/Dependientes/Edit', [
            'dependiente' => $dependiente,
            'parentescos' => $parentescos,
            'tiposDocumento' => $tiposDocumento
        ]);
    }

    /**
     * Actualizar dependiente
     */
    public function update(Request $request, Dependiente $dependiente)
    {
        $user = Auth::user();

        // Verificar que el dependiente pertenece al usuario autenticado
        if ($dependiente->tutor_id !== $user->id) {
            abort(403, 'No tienes permisos para editar este dependiente.');
        }

        $request->validate([
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => ['required', Rule::in(['DNI', 'CE', 'PASAPORTE'])],
            'numero_documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dependientes')
                    ->where('tutor_id', $user->id)
                    ->ignore($dependiente->id)
            ],
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => ['required', Rule::in(['M', 'F'])],
            'parentesco' => ['required', Rule::in(['hijo/a', 'nieto/a', 'sobrino/a', 'hermano/a', 'padre/madre', 'abuelo/a', 'otro'])],
            'telefono' => 'nullable|string|max:15',
            'observaciones' => 'nullable|string|max:500',
            'activo' => 'boolean'
        ], [
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.unique' => 'Ya tienes un dependiente registrado con este número de documento.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'genero.required' => 'El género es obligatorio.',
            'parentesco.required' => 'El parentesco es obligatorio.'
        ]);

        $dependiente->update([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'parentesco' => $request->parentesco,
            'telefono' => $request->telefono,
            'observaciones' => $request->observaciones,
            'activo' => $request->boolean('activo', true)
        ]);

        return redirect()->route('paciente.dependientes.index')
            ->with('success', 'Dependiente actualizado exitosamente.');
    }

    /**
     * Eliminar dependiente
     */
    public function destroy(Dependiente $dependiente)
    {
        $user = Auth::user();

        // Verificar que el dependiente pertenece al usuario autenticado
        if ($dependiente->tutor_id !== $user->id) {
            abort(403, 'No tienes permisos para eliminar este dependiente.');
        }

        $nombreCompleto = $dependiente->nombre_completo;
        $dependiente->delete();

        return redirect()->route('paciente.dependientes.index')
            ->with('success', "Dependiente {$nombreCompleto} eliminado exitosamente.");
    }
}
