<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Dependiente;
use App\Models\EsquemaVacunacion;
use App\Models\Recordatorio;
use App\Models\AplicacionVacuna;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerarRecordatoriosAutomaticos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordatorios:generar {--user_id=} {--dependiente_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera recordatorios automáticos de vacunación basados en esquemas y edades';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando generación de recordatorios automáticos...');

        $userIdFiltro = $this->option('user_id');
        $dependienteIdFiltro = $this->option('dependiente_id');

        $recordatoriosGenerados = 0;

        if ($dependienteIdFiltro) {
            // Generar para un dependiente específico
            $dependiente = Dependiente::find($dependienteIdFiltro);
            if ($dependiente) {
                $recordatoriosGenerados += $this->generarRecordatoriosParaDependiente($dependiente);
            } else {
                $this->error("Dependiente con ID {$dependienteIdFiltro} no encontrado");
                return 1;
            }
        } else {
            // Obtener usuarios pacientes
            $query = User::role('PACIENTE')->with('dependientes');

            if ($userIdFiltro) {
                $query->where('id', $userIdFiltro);
            }

            $pacientes = $query->get();

            foreach ($pacientes as $paciente) {
                // Generar recordatorios para el paciente
                $recordatoriosGenerados += $this->generarRecordatoriosParaUsuario($paciente);

                // Generar recordatorios para sus dependientes
                foreach ($paciente->dependientes()->activos()->get() as $dependiente) {
                    $recordatoriosGenerados += $this->generarRecordatoriosParaDependiente($dependiente);
                }
            }
        }

        $this->info("✅ Proceso completado. Se generaron {$recordatoriosGenerados} recordatorios.");

        return 0;
    }

    private function generarRecordatoriosParaUsuario(User $usuario)
    {
        $recordatoriosGenerados = 0;

        if (!$usuario->fecha_nacimiento) {
            return $recordatoriosGenerados;
        }

        $edadMeses = $usuario->getEdadEnMeses();

        $this->info("📋 Procesando usuario: {$usuario->name} (Edad: {$edadMeses} meses)");

        // Obtener esquemas de vacunación apropiados
        $esquemas = EsquemaVacunacion::activos()
            ->where('edad_inicio', '<=', $edadMeses)
            ->where(function($query) use ($edadMeses) {
                $query->where('edad_fin', '>=', $edadMeses)
                      ->orWhereNull('edad_fin');
            })
            ->with(['dosisVacunas.vacuna'])
            ->get();

        foreach ($esquemas as $esquema) {
            foreach ($esquema->dosisVacunas as $dosis) {
                if ($this->debeGenerarRecordatorio($usuario, $dosis)) {
                    $fechaRecordatorio = $this->calcularFechaRecordatorio($usuario->fecha_nacimiento, $dosis);

                    if ($fechaRecordatorio >= now()->toDateString()) {
                        $recordatorio = Recordatorio::create([
                            'user_id' => $usuario->id,
                            'vacuna_id' => $dosis->vacuna_id,
                            'tipo' => 'vacuna_proxima',
                            'titulo' => "Vacuna pendiente: {$dosis->vacuna->nombre}",
                            'mensaje' => "Es momento de aplicarse la vacuna {$dosis->vacuna->nombre} (dosis {$dosis->numero_dosis}). Recuerde agendar su cita.",
                            'fecha_recordatorio' => $fechaRecordatorio,
                            'estado' => 'pendiente',
                            'metodo_envio' => 'sistema'
                        ]);

                        $recordatoriosGenerados++;
                        $this->line("   ✅ Recordatorio creado: {$dosis->vacuna->nombre} para {$fechaRecordatorio}");
                    }
                }
            }
        }

        return $recordatoriosGenerados;
    }

    private function generarRecordatoriosParaDependiente(Dependiente $dependiente)
    {
        $recordatoriosGenerados = 0;
        $edadMeses = $dependiente->edad_en_meses;

        $this->info("👶 Procesando dependiente: {$dependiente->nombre_completo} (Edad: {$edadMeses} meses)");

        // Obtener esquemas de vacunación apropiados
        $esquemas = EsquemaVacunacion::activos()
            ->where('edad_inicio', '<=', $edadMeses)
            ->where(function($query) use ($edadMeses) {
                $query->where('edad_fin', '>=', $edadMeses)
                      ->orWhereNull('edad_fin');
            })
            ->with(['dosisVacunas.vacuna'])
            ->get();

        foreach ($esquemas as $esquema) {
            foreach ($esquema->dosisVacunas as $dosis) {
                if ($this->debeGenerarRecordatorioDependiente($dependiente, $dosis)) {
                    $fechaRecordatorio = $this->calcularFechaRecordatorio($dependiente->fecha_nacimiento, $dosis);

                    if ($fechaRecordatorio >= now()->toDateString()) {
                        $recordatorio = Recordatorio::create([
                            'user_id' => $dependiente->tutor_id,
                            'vacuna_id' => $dosis->vacuna_id,
                            'tipo' => 'vacuna_proxima',
                            'titulo' => "Vacuna pendiente para {$dependiente->nombres}",
                            'mensaje' => "Es momento de aplicar la vacuna {$dosis->vacuna->nombre} (dosis {$dosis->numero_dosis}) a {$dependiente->nombre_completo}. Recuerde agendar la cita.",
                            'fecha_recordatorio' => $fechaRecordatorio,
                            'estado' => 'pendiente',
                            'metodo_envio' => 'sistema'
                        ]);

                        $recordatoriosGenerados++;
                        $this->line("   ✅ Recordatorio creado: {$dosis->vacuna->nombre} para {$dependiente->nombres} el {$fechaRecordatorio}");
                    }
                }
            }
        }

        return $recordatoriosGenerados;
    }

    private function debeGenerarRecordatorio(User $usuario, $dosis)
    {
        // Verificar si ya existe un recordatorio para esta vacuna y dosis
        $recordatorioExiste = Recordatorio::where('user_id', $usuario->id)
            ->where('vacuna_id', $dosis->vacuna_id)
            ->where('titulo', 'LIKE', '%' . $dosis->vacuna->nombre . '%')
            ->exists();

        if ($recordatorioExiste) {
            return false;
        }

        // Verificar si ya tiene esta vacuna aplicada
        $vacunaAplicada = AplicacionVacuna::where('user_id', $usuario->id)
            ->where('vacuna_id', $dosis->vacuna_id)
            ->where('numero_dosis', $dosis->numero_dosis)
            ->exists();

        return !$vacunaAplicada;
    }

    private function debeGenerarRecordatorioDependiente(Dependiente $dependiente, $dosis)
    {
        // Verificar si ya existe un recordatorio para este dependiente
        $recordatorioExiste = Recordatorio::where('user_id', $dependiente->tutor_id)
            ->where('vacuna_id', $dosis->vacuna_id)
            ->where('titulo', 'LIKE', '%' . $dependiente->nombres . '%')
            ->where('mensaje', 'LIKE', '%' . $dosis->vacuna->nombre . '%')
            ->exists();

        if ($recordatorioExiste) {
            return false;
        }

        // Si el dependiente está vinculado a un usuario registrado, verificar sus aplicaciones
        if ($dependiente->dependiente_user_id) {
            $vacunaAplicada = AplicacionVacuna::where('user_id', $dependiente->dependiente_user_id)
                ->where('vacuna_id', $dosis->vacuna_id)
                ->where('numero_dosis', $dosis->numero_dosis)
                ->exists();

            return !$vacunaAplicada;
        }

        // Si no está vinculado, asumir que no tiene vacunas aplicadas
        return true;
    }

    private function calcularFechaRecordatorio($fechaNacimiento, $dosis)
    {
        $fechaNac = Carbon::parse($fechaNacimiento);

        if ($dosis->edad_aplicacion) {
            // Calcular fecha basada en edad en meses
            $fechaAplicacion = $fechaNac->copy()->addMonths($dosis->edad_aplicacion);

            // Programar recordatorio una semana antes
            return $fechaAplicacion->subWeek()->toDateString();
        }

        if ($dosis->dias_despues_anterior && $dosis->dias_despues_anterior > 0) {
            // Por simplicidad, asumir 30 días si no tenemos la fecha de la dosis anterior
            return now()->addDays($dosis->dias_despues_anterior - 7)->toDateString();
        }

        // Por defecto, programar para la próxima semana
        return now()->addWeek()->toDateString();
    }
}
