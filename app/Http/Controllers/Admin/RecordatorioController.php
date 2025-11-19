<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class RecordatorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recordatorio::with([
            'usuario:id,name,apellidos,fecha_nacimiento',
            'vacuna:id,nombre',
            'cita:id,titulo',
            'dependiente:id,nombres,apellidos,fecha_nacimiento'
        ]);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                    ->orWhere('mensaje', 'like', '%' . $request->search . '%')
                    ->orWhereHas('usuario', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('apellidos', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_recordatorio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_recordatorio', '<=', $request->fecha_hasta);
        }

        $recordatorios = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Datos para filtros
        $tipos = [
            'vacuna_proxima' => 'Vacuna Próxima',
            'cita_programada' => 'Cita Programada',
            'refuerzo_pendiente' => 'Refuerzo Pendiente',
            'recordatorio_personal' => 'Recordatorio Personal'
        ];

        $estados = [
            'pendiente' => 'Pendiente',
            'enviado' => 'Enviado',
            'leido' => 'Leído',
            'programado' => 'Programado'
        ];

        return Inertia::render('Admin/Recordatorios/Index', [
            'recordatorios' => $recordatorios,
            'filters' => $request->only(['search', 'estado', 'tipo', 'fecha_desde', 'fecha_hasta']),
            'tipos' => $tipos,
            'estados' => $estados,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recordatorio $recordatorio)
    {
        $recordatorio->load(['usuario', 'vacuna', 'cita', 'dependiente']);

        return Inertia::render('Admin/Recordatorios/Show', [
            'recordatorio' => $recordatorio,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recordatorio $recordatorio)
    {
        $recordatorio->load(['usuario', 'vacuna', 'cita', 'dependiente']);

        return Inertia::render('Admin/Recordatorios/Edit', [
            'recordatorio' => $recordatorio,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recordatorio $recordatorio)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'fecha_recordatorio' => 'required|date',
            'hora_recordatorio' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $recordatorio->update($request->only(['titulo', 'fecha_recordatorio', 'hora_recordatorio']));

        return redirect()->route('admin.recordatorios.index')
            ->with('success', 'Recordatorio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recordatorio $recordatorio)
    {
        $titulo = $recordatorio->titulo;
        $recordatorio->delete();

        return response()->json([
            'message' => 'Recordatorio eliminado exitosamente.',
            'titulo' => $titulo
        ]);
    }

    /**
     * Marcar recordatorio como enviado
     */
    public function marcarEnviado(Recordatorio $recordatorio)
    {
        $recordatorio->update([
            'estado' => 'enviado',
            'enviado_en' => now()
        ]);

        return response()->json([
            'message' => 'Recordatorio marcado como enviado exitosamente.',
            'recordatorio' => $recordatorio
        ]);
    }
}
