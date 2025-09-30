<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DosisVacuna;
use App\Models\Vacuna;
use App\Models\EsquemaVacunacion;

class DosisVacunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los esquemas y vacunas para crear las relaciones
        $esquemas = EsquemaVacunacion::all()->keyBy('nombre');
        $vacunas = Vacuna::all()->keyBy('nombre');

        // Definir las dosis según el Esquema Nacional de Vacunación del Perú
        $dosisVacunas = [
            // RECIÉN NACIDOS (0 meses)
            [
                'esquema' => 'Recién Nacidos',
                'vacuna' => 'BCG',
                'numero_dosis' => 1,
                'edad_aplicacion' => 0,
                'observaciones' => 'Dosis única al nacer'
            ],
            [
                'esquema' => 'Recién Nacidos',
                'vacuna' => 'Hepatitis B',
                'numero_dosis' => 1,
                'edad_aplicacion' => 0,
                'observaciones' => 'Primera dosis al nacer'
            ],

            // 2 MESES
            [
                'esquema' => '2 Meses',
                'vacuna' => 'Pentavalente (DPT-HvB-Hib)',
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'observaciones' => 'Primera dosis de la serie'
            ],
            [
                'esquema' => '2 Meses',
                'vacuna' => 'Antipolio IPV',
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'observaciones' => 'Primera dosis inyectable'
            ],
            [
                'esquema' => '2 Meses',
                'vacuna' => 'Rotavirus',
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'observaciones' => 'Primera dosis oral'
            ],
            [
                'esquema' => '2 Meses',
                'vacuna' => 'Antineumocócica',
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'observaciones' => 'Primera dosis'
            ],

            // 4 MESES
            [
                'esquema' => '4 Meses',
                'vacuna' => 'Pentavalente (DPT-HvB-Hib)',
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'observaciones' => 'Segunda dosis de refuerzo'
            ],
            [
                'esquema' => '4 Meses',
                'vacuna' => 'Antipolio IPV',
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'observaciones' => 'Segunda dosis inyectable'
            ],
            [
                'esquema' => '4 Meses',
                'vacuna' => 'Rotavirus',
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'observaciones' => 'Segunda y última dosis oral'
            ],
            [
                'esquema' => '4 Meses',
                'vacuna' => 'Antineumocócica',
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'observaciones' => 'Segunda dosis'
            ],

            // 6 MESES
            [
                'esquema' => '6 Meses',
                'vacuna' => 'Pentavalente (DPT-HvB-Hib)',
                'numero_dosis' => 3,
                'edad_aplicacion' => 6,
                'observaciones' => 'Tercera y última dosis de la serie básica'
            ],
            [
                'esquema' => '6 Meses',
                'vacuna' => 'Antipolio APO',
                'numero_dosis' => 1,
                'edad_aplicacion' => 6,
                'observaciones' => 'Primera dosis oral'
            ],
            [
                'esquema' => '6 Meses',
                'vacuna' => 'Influenza Pediátrica',
                'numero_dosis' => 1,
                'edad_aplicacion' => 6,
                'observaciones' => 'Primera dosis (menores de 2 años requieren 2 dosis)'
            ],

            // 7 MESES
            [
                'esquema' => '7 Meses',
                'vacuna' => 'Influenza Pediátrica',
                'numero_dosis' => 2,
                'edad_aplicacion' => 7,
                'observaciones' => 'Segunda dosis para completar protección inicial'
            ],

            // 12 MESES
            [
                'esquema' => '12 Meses',
                'vacuna' => 'SPR (Triple Viral)',
                'numero_dosis' => 1,
                'edad_aplicacion' => 12,
                'observaciones' => 'Primera dosis'
            ],
            [
                'esquema' => '12 Meses',
                'vacuna' => 'Antineumocócica',
                'numero_dosis' => 3,
                'edad_aplicacion' => 12,
                'observaciones' => 'Tercera y última dosis'
            ],
            [
                'esquema' => '12 Meses',
                'vacuna' => 'Varicela',
                'numero_dosis' => 1,
                'edad_aplicacion' => 12,
                'observaciones' => 'Dosis única'
            ],
            [
                'esquema' => '12 Meses',
                'vacuna' => 'Influenza Anual',
                'numero_dosis' => 1,
                'edad_aplicacion' => 12,
                'observaciones' => 'Dosis anual'
            ],

            // 15 MESES
            [
                'esquema' => '15 Meses',
                'vacuna' => 'Antiamarílica (AMA)',
                'numero_dosis' => 1,
                'edad_aplicacion' => 15,
                'observaciones' => 'Dosis única'
            ],
            [
                'esquema' => '15 Meses',
                'vacuna' => 'Hepatitis A',
                'numero_dosis' => 1,
                'edad_aplicacion' => 15,
                'observaciones' => 'Dosis única'
            ],

            // 18 MESES
            [
                'esquema' => '18 Meses',
                'vacuna' => 'DPT',
                'numero_dosis' => 1,
                'edad_aplicacion' => 18,
                'observaciones' => 'Primer refuerzo'
            ],
            [
                'esquema' => '18 Meses',
                'vacuna' => 'Antipolio APO',
                'numero_dosis' => 2,
                'edad_aplicacion' => 18,
                'observaciones' => 'Primer refuerzo oral'
            ],
            [
                'esquema' => '18 Meses',
                'vacuna' => 'SPR (Triple Viral)',
                'numero_dosis' => 2,
                'edad_aplicacion' => 18,
                'observaciones' => 'Segunda dosis'
            ],

            // 2 AÑOS (24 meses)
            [
                'esquema' => '2 Años',
                'vacuna' => 'Influenza Anual',
                'numero_dosis' => 1,
                'edad_aplicacion' => 24,
                'observaciones' => 'Dosis anual'
            ],

            // 3 AÑOS (36 meses)
            [
                'esquema' => '3 Años',
                'vacuna' => 'Influenza Anual',
                'numero_dosis' => 1,
                'edad_aplicacion' => 36,
                'observaciones' => 'Dosis anual'
            ],

            // 4 AÑOS (48 meses)
            [
                'esquema' => '4 Años',
                'vacuna' => 'Influenza Anual',
                'numero_dosis' => 1,
                'edad_aplicacion' => 48,
                'observaciones' => 'Dosis anual'
            ],
            [
                'esquema' => '4 Años',
                'vacuna' => 'DPT',
                'numero_dosis' => 2,
                'edad_aplicacion' => 48,
                'observaciones' => 'Segundo refuerzo'
            ],
            [
                'esquema' => '4 Años',
                'vacuna' => 'Antipolio APO',
                'numero_dosis' => 3,
                'edad_aplicacion' => 48,
                'observaciones' => 'Segundo refuerzo oral'
            ],

            // ADOLESCENTES VPH (9-13 años)
            [
                'esquema' => 'Adolescentes VPH',
                'vacuna' => 'VPH',
                'numero_dosis' => 1,
                'edad_aplicacion' => 108, // 9 años
                'observaciones' => 'Dosis única para niñas y niños'
            ],

            // JÓVENES VPH (14-18 años)
            [
                'esquema' => 'Jóvenes VPH',
                'vacuna' => 'VPH',
                'numero_dosis' => 1,
                'edad_aplicacion' => 168, // 14 años
                'observaciones' => 'Dosis de recuperación si no se aplicó antes'
            ],

            // ADULTOS
            [
                'esquema' => 'Adultos',
                'vacuna' => 'Antiamarílica (AMA)',
                'numero_dosis' => 1,
                'edad_aplicacion' => 216, // 18 años
                'observaciones' => 'Para viajeros a zonas endémicas'
            ],
            [
                'esquema' => 'Adultos',
                'vacuna' => 'Hepatitis B',
                'numero_dosis' => 1,
                'edad_aplicacion' => 216, // 18 años
                'observaciones' => 'Para personas con comorbilidades'
            ],
            [
                'esquema' => 'Adultos',
                'vacuna' => 'Influenza Anual',
                'numero_dosis' => 1,
                'edad_aplicacion' => 216, // 18 años
                'observaciones' => 'Para personas con comorbilidades'
            ],

            // GESTANTES
            [
                'esquema' => 'Gestantes',
                'vacuna' => 'Hepatitis B',
                'numero_dosis' => 1,
                'edad_aplicacion' => null,
                'observaciones' => 'Tres dosis en intervalos de 2 meses'
            ],
            [
                'esquema' => 'Gestantes',
                'vacuna' => 'Hepatitis B',
                'numero_dosis' => 2,
                'edad_aplicacion' => null,
                'observaciones' => 'Segunda dosis a los 2 meses'
            ],
            [
                'esquema' => 'Gestantes',
                'vacuna' => 'Hepatitis B',
                'numero_dosis' => 3,
                'edad_aplicacion' => null,
                'observaciones' => 'Tercera dosis a los 4 meses'
            ],
            [
                'esquema' => 'Gestantes',
                'vacuna' => 'DT',
                'numero_dosis' => 1,
                'edad_aplicacion' => null,
                'observaciones' => 'Protección contra tétano y difteria'
            ],
            [
                'esquema' => 'Gestantes',
                'vacuna' => 'Tdap',
                'numero_dosis' => 1,
                'edad_aplicacion' => null,
                'observaciones' => 'A partir de las 20-36 semanas de embarazo'
            ],
            [
                'esquema' => 'Gestantes',
                'vacuna' => 'Influenza Gestante',
                'numero_dosis' => 1,
                'edad_aplicacion' => null,
                'observaciones' => 'En cualquier trimestre de gestación'
            ],

            // ADULTOS MAYORES (60+ años)
            [
                'esquema' => 'Adultos Mayores',
                'vacuna' => 'Influenza Anual',
                'numero_dosis' => 1,
                'edad_aplicacion' => 720, // 60 años
                'observaciones' => 'Dosis anual'
            ],
            [
                'esquema' => 'Adultos Mayores',
                'vacuna' => 'Antineumocócica Adulto',
                'numero_dosis' => 1,
                'edad_aplicacion' => 720, // 60 años
                'observaciones' => 'Dosis única'
            ]
        ];

        // Crear las dosis de vacunas
        // Calcular valores por defecto para `dias_despues_anterior` cuando no se provea explícitamente.
        // Si la dosis actual y la anterior tienen `edad_aplicacion`, estimamos el intervalo en días
        // usando la diferencia en meses * 30.
        $indexByVacuna = [];
        foreach ($dosisVacunas as $i => $d) {
            $vacName = $d['vacuna'];
            $numero = $d['numero_dosis'];
            if (!isset($indexByVacuna[$vacName])) $indexByVacuna[$vacName] = [];
            $indexByVacuna[$vacName][$numero] = $i;
        }

        foreach ($dosisVacunas as $dosisKey => &$dosisData) {
            // Si ya viene definido, respetarlo
            if (array_key_exists('dias_despues_anterior', $dosisData)) {
                continue;
            }

            if ($dosisData['numero_dosis'] > 1) {
                $vacName = $dosisData['vacuna'];
                $prevNum = $dosisData['numero_dosis'] - 1;
                if (isset($indexByVacuna[$vacName][$prevNum])) {
                    $prevEntry = $dosisVacunas[$indexByVacuna[$vacName][$prevNum]];
                    if (isset($dosisData['edad_aplicacion']) && isset($prevEntry['edad_aplicacion']) && $dosisData['edad_aplicacion'] !== null && $prevEntry['edad_aplicacion'] !== null) {
                        $mesesDiff = intval($dosisData['edad_aplicacion']) - intval($prevEntry['edad_aplicacion']);
                        // Si la diferencia es mayor a 0, estimamos días = mesesDiff * 30
                        if ($mesesDiff > 0) {
                            $dosisData['dias_despues_anterior'] = $mesesDiff * 30;
                        } else {
                            $dosisData['dias_despues_anterior'] = null;
                        }
                    } else {
                        $dosisData['dias_despues_anterior'] = null;
                    }
                } else {
                    $dosisData['dias_despues_anterior'] = null;
                }
            } else {
                $dosisData['dias_despues_anterior'] = null;
            }
        }
        unset($dosisData); // limpiar referencia

        foreach ($dosisVacunas as $dosisData) {
            $esquema = $esquemas->get($dosisData['esquema']);
            $vacuna = $vacunas->get($dosisData['vacuna']);

            if ($esquema && $vacuna) {
                DosisVacuna::updateOrCreate([
                    'esquema_vacunacion_id' => $esquema->id,
                    'vacuna_id' => $vacuna->id,
                    'numero_dosis' => $dosisData['numero_dosis'],
                ], [
                    'edad_aplicacion' => $dosisData['edad_aplicacion'],
                    'dias_despues_anterior' => $dosisData['dias_despues_anterior'] ?? null,
                    'observaciones' => $dosisData['observaciones'],
                    'activo' => true
                ]);
            }
        }
    }
}
