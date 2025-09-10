<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CompletarRegistro
{
    public function create()
    {
        $user = Auth::user();
        if (empty($user->se_registro)) {
            return Inertia::render(
                'Auth/CompletarRegistro',
                compact('user')
            );
        }
        return redirect()->route('dashboard');
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'apellidos' => ['required', 'string', 'max:255'],
                'telefono' => ['required', 'integer', 'digits:9'],
                'tipo_documento' => ['required', 'string', 'max:255'],
                'numero_documento' => ['required', 'string', 'max:255'],
                'fecha_nacimiento' => ['required', 'date'],
                'fecha_emision' => ['required', 'date'],
                'genero' => ['required', 'string', 'in:M,F,O'],
            ],
            [
                'telefono.digits' => 'El teléfono debe tener 9 dígitos.',
                'genero.in' => 'El campo género debe ser M, F, O.',
            ]
        )->validate();

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->apellidos = $request->apellidos;
        $user->telefono = $request->telefono;
        $user->se_registro = 1;
        $user->tipo_documento = $request->tipo_documento;
        $user->numero_documento = $request->numero_documento;
        $user->fecha_nacimiento = $request->fecha_nacimiento;
        $user->fecha_emision = $request->fecha_emision;
        $user->genero = $request->genero;

        $user->save();
        return redirect()->intended(route('dashboard'));
    }
}
