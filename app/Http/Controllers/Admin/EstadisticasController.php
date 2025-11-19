<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vacuna;
use App\Models\AplicacionVacuna;
use App\Models\Cita;
use App\Models\Recordatorio;
use App\Models\CentroSalud;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Dependiente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function index()
    {
        // Estadísticas generales del sistema
        $estadisticasGenerales = $this->getEstadisticasGenerales();

        // Estadísticas por roles
        $estadisticasRoles = $this->getEstadisticasRoles();

        // Estadísticas de vacunación
        $estadisticasVacunacion = $this->getEstadisticasVacunacion();

        // Estadísticas geográficas
        $estadisticasGeograficas = $this->getEstadisticasGeograficas();

        // Estadísticas de citas
        $estadisticasCitas = $this->getEstadisticasCitas();

        // Estadísticas de recordatorios
        $estadisticasRecordatorios = $this->getEstadisticasRecordatorios();

        // Estadísticas temporales (últimos 12 meses)
        $estadisticasTemporales = $this->getEstadisticasTemporales();

        return Inertia::render('Admin/Estadisticas/Index', [
            'estadisticasGenerales' => $estadisticasGenerales,
            'estadisticasRoles' => $estadisticasRoles,
            'estadisticasVacunacion' => $estadisticasVacunacion,
            'estadisticasGeograficas' => $estadisticasGeograficas,
            'estadisticasCitas' => $estadisticasCitas,
            'estadisticasRecordatorios' => $estadisticasRecordatorios,
            'estadisticasTemporales' => $estadisticasTemporales,
        ]);
    }

    private function getEstadisticasGenerales()
    {
        return [
            'totalUsuarios' => User::count(),
            'totalDependientes' => Dependiente::count(),
            'totalVacunas' => Vacuna::count(),
            'totalAplicaciones' => AplicacionVacuna::count(),
            'totalCitas' => Cita::count(),
            'totalRecordatorios' => Recordatorio::count(),
            'totalCentrosSalud' => CentroSalud::count(),
            'totalDepartamentos' => Departamento::count(),
            'totalProvincias' => Provincia::count(),
        ];
    }

    private function getEstadisticasRoles()
    {
        return [
            'administradores' => User::role('ADMINISTRADOR')->count(),
            'medicos' => User::role('MEDICO')->count(),
            'pacientes' => User::role('PACIENTE')->count(),
            'usuariosSinRol' => User::whereDoesntHave('roles')->count(),
        ];
    }

    private function getEstadisticasVacunacion()
    {
        // Vacunas más aplicadas
        $vacunasMasAplicadas = DB::table('aplicacion_vacunas')
            ->join('vacunas', 'aplicacion_vacunas.vacuna_id', '=', 'vacunas.id')
            ->select('vacunas.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('vacunas.id', 'vacunas.nombre')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Aplicaciones por mes (últimos 12 meses)
        $aplicacionesPorMes = DB::table('aplicacion_vacunas')
            ->select(
                DB::raw('YEAR(fecha_aplicacion) as year'),
                DB::raw('MONTH(fecha_aplicacion) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha_aplicacion', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'total' => $item->total,
                ];
            });

        // Estado de dosis aplicadas
        $estadoDosis = DB::table('aplicacion_vacunas')
            ->join('dosis_vacunas', 'aplicacion_vacunas.dosis_vacuna_id', '=', 'dosis_vacunas.id')
            ->select('dosis_vacunas.numero_dosis', DB::raw('COUNT(*) as total'))
            ->groupBy('dosis_vacunas.numero_dosis')
            ->orderBy('dosis_vacunas.numero_dosis')
            ->get();

        return [
            'vacunasMasAplicadas' => $vacunasMasAplicadas,
            'aplicacionesPorMes' => $aplicacionesPorMes,
            'estadoDosis' => $estadoDosis,
            'totalAplicacionesHoy' => AplicacionVacuna::whereDate('fecha_aplicacion', today())->count(),
            'totalAplicacionesEstaSemana' => AplicacionVacuna::whereBetween('fecha_aplicacion', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];
    }

    private function getEstadisticasGeograficas()
    {
        // Aplicaciones por departamento
        $aplicacionesPorDepartamento = DB::table('aplicacion_vacunas')
            ->join('centros_salud', 'aplicacion_vacunas.centro_salud_id', '=', 'centros_salud.id')
            ->join('distritos', 'centros_salud.distrito_codigo', '=', 'distritos.codigo_ubigeo')
            ->join('provincias', 'distritos.provincia_codigo', '=', 'provincias.codigo_ubigeo')
            ->join('departamentos', 'provincias.departamento_codigo', '=', 'departamentos.codigo_ubigeo')
            ->select('departamentos.nombre as departamento', DB::raw('COUNT(*) as total'))
            ->groupBy('departamentos.id', 'departamentos.nombre')
            ->orderBy('total', 'desc')
            ->get();

        // Centros de salud más activos
        $centrosMasActivos = DB::table('aplicacion_vacunas')
            ->join('centros_salud', 'aplicacion_vacunas.centro_salud_id', '=', 'centros_salud.id')
            ->select('centros_salud.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('centros_salud.id', 'centros_salud.nombre')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return [
            'aplicacionesPorDepartamento' => $aplicacionesPorDepartamento,
            'centrosMasActivos' => $centrosMasActivos,
        ];
    }

    private function getEstadisticasCitas()
    {
        // Citas por estado
        $citasPorEstado = DB::table('citas')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get()
            ->map(function ($item) {
                return [
                    'estado' => $this->getEstadoCitaTexto($item->estado),
                    'total' => $item->total,
                    'color' => $this->getEstadoCitaColor($item->estado),
                ];
            });

        // Citas por mes
        $citasPorMes = DB::table('citas')
            ->select(
                DB::raw('YEAR(fecha_hora) as year'),
                DB::raw('MONTH(fecha_hora) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha_hora', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'total' => $item->total,
                ];
            });

        return [
            'citasPorEstado' => $citasPorEstado,
            'citasPorMes' => $citasPorMes,
            'citasHoy' => Cita::whereDate('fecha_hora', today())->count(),
            'citasEstaSemana' => Cita::whereBetween('fecha_hora', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];
    }

    private function getEstadisticasRecordatorios()
    {
        // Recordatorios por estado
        $recordatoriosPorEstado = DB::table('recordatorios')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get()
            ->map(function ($item) {
                return [
                    'estado' => $this->getEstadoRecordatorioTexto($item->estado),
                    'total' => $item->total,
                    'color' => $this->getEstadoRecordatorioColor($item->estado),
                ];
            });

        // Recordatorios por tipo
        $recordatoriosPorTipo = DB::table('recordatorios')
            ->select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->get()
            ->map(function ($item) {
                return [
                    'tipo' => $this->getTipoRecordatorioTexto($item->tipo),
                    'total' => $item->total,
                ];
            });

        return [
            'recordatoriosPorEstado' => $recordatoriosPorEstado,
            'recordatoriosPorTipo' => $recordatoriosPorTipo,
            'recordatoriosHoy' => Recordatorio::whereDate('fecha_recordatorio', Carbon::today())->count(),
            'recordatoriosEnviados' => Recordatorio::where('estado', 'enviado')->count(),
            'recordatoriosPendientes' => Recordatorio::where('estado', 'pendiente')->count(),
        ];
    }

    private function getEstadisticasTemporales()
    {
        // Usuarios registrados por mes (últimos 12 meses)
        $usuariosPorMes = DB::table('users')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'total' => $item->total,
                ];
            });

        // Actividad general por mes
        $actividadPorMes = [];
        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $actividadPorMes[] = [
                'mes' => $fecha->format('M Y'),
                'usuarios' => User::whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)->count(),
                'aplicaciones' => AplicacionVacuna::whereYear('fecha_aplicacion', $fecha->year)
                    ->whereMonth('fecha_aplicacion', $fecha->month)->count(),
                'citas' => Cita::whereYear('fecha_hora', $fecha->year)
                    ->whereMonth('fecha_hora', $fecha->month)->count(),
                'recordatorios' => Recordatorio::whereYear('fecha_recordatorio', $fecha->year)
                    ->whereMonth('fecha_recordatorio', $fecha->month)->count(),
            ];
        }

        return [
            'usuariosPorMes' => $usuariosPorMes,
            'actividadPorMes' => $actividadPorMes,
        ];
    }

    private function getEstadoCitaTexto($estado)
    {
        return match($estado) {
            'programada' => 'Programada',
            'confirmada' => 'Confirmada',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
            default => ucfirst($estado),
        };
    }

    private function getEstadoCitaColor($estado)
    {
        return match($estado) {
            'programada' => 'bg-yellow-100 text-yellow-800',
            'confirmada' => 'bg-blue-100 text-blue-800',
            'completada' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    private function getEstadoRecordatorioTexto($estado)
    {
        return match($estado) {
            'pendiente' => 'Pendiente',
            'enviado' => 'Enviado',
            'leido' => 'Leído',
            default => ucfirst($estado),
        };
    }

    private function getEstadoRecordatorioColor($estado)
    {
        return match($estado) {
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'enviado' => 'bg-blue-100 text-blue-800',
            'leido' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    private function getTipoRecordatorioTexto($tipo)
    {
        return match($tipo) {
            'cita' => 'Cita Programada',
            'vacuna' => 'Vacunación',
            'recordatorio' => 'Recordatorio General',
            default => ucfirst($tipo),
        };
    }
}
