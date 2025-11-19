<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use App\Models\AplicacionVacuna;
use App\Models\Cita;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas del sistema
        $stats = [
            'totalVacunas' => Vacuna::count(),
            'pacientesVacunados' => $this->getPacientesVacunadosCount(),
            'citasPendientes' => Cita::whereIn('estado', ['programada', 'confirmada'])->count(),
            'recordatoriosHoy' => Recordatorio::whereDate('fecha_recordatorio', today())->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }

    private function getPacientesVacunadosCount()
    {
        // Contar usuarios únicos que han recibido vacunas (tanto directos como a través de dependientes)
        $usuariosDirectos = AplicacionVacuna::whereNotNull('user_id')->distinct('user_id')->count();
        $dependientes = AplicacionVacuna::whereNotNull('dependiente_id')->distinct('dependiente_id')->count();

        return $usuariosDirectos + $dependientes;
    }
}
