<?php

namespace Database\Seeders;

use App\Models\Vacuna;
use App\Models\EsquemaVacunacion;
use App\Models\DosisVacuna;
use Illuminate\Database\Seeder;

class EsquemasVacunacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear vacunas básicas si no existen
        $vacunas = [
            [
                'nombre' => 'BCG',
                'descripcion' => 'Vacuna contra la tuberculosis',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Bacteriana',
                'dosis_requeridas' => 1,
                'edad_minima' => 0,
                'edad_maxima' => 1,
                'activa' => true
            ],
            [
                'nombre' => 'Hepatitis B',
                'descripcion' => 'Vacuna contra la hepatitis B',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Viral',
                'dosis_requeridas' => 3,
                'edad_minima' => 0,
                'edad_maxima' => 60,
                'activa' => true
            ],
            [
                'nombre' => 'Pentavalente',
                'descripcion' => 'Vacuna contra difteria, tétanos, tos ferina, hepatitis B y Haemophilus influenzae tipo b',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Combinada',
                'dosis_requeridas' => 3,
                'edad_minima' => 2,
                'edad_maxima' => 24,
                'activa' => true
            ],
            [
                'nombre' => 'Polio',
                'descripcion' => 'Vacuna contra la poliomielitis',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Viral',
                'dosis_requeridas' => 3,
                'edad_minima' => 2,
                'edad_maxima' => 18,
                'activa' => true
            ],
            [
                'nombre' => 'Rotavirus',
                'descripcion' => 'Vacuna contra el rotavirus',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Viral',
                'dosis_requeridas' => 2,
                'edad_minima' => 2,
                'edad_maxima' => 6,
                'activa' => true
            ],
            [
                'nombre' => 'Neumococo',
                'descripcion' => 'Vacuna contra el neumococo',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Bacteriana',
                'dosis_requeridas' => 3,
                'edad_minima' => 2,
                'edad_maxima' => 24,
                'activa' => true
            ],
            [
                'nombre' => 'SPR',
                'descripcion' => 'Vacuna contra sarampión, paperas y rubéola',
                'fabricante' => 'Laboratorio Nacional',
                'tipo' => 'Viral',
                'dosis_requeridas' => 2,
                'edad_minima' => 12,
                'edad_maxima' => 24,
                'activa' => true
            ]
        ];

        foreach ($vacunas as $vacunaData) {
            Vacuna::firstOrCreate(
                ['nombre' => $vacunaData['nombre']],
                $vacunaData
            );
        }

        // Crear esquema de vacunación para niños (0-24 meses)
        $esquemaNinos = EsquemaVacunacion::firstOrCreate(
            ['nombre' => 'Esquema Regular para Niños'],
            [
                'descripcion' => 'Esquema de vacunación regular para niños de 0 a 24 meses según el MINSA',
                'grupo_edad' => 'niños',
                'edad_inicio' => 0,
                'edad_fin' => 24,
                'obligatorio' => true,
                'activo' => true
            ]
        );

        // Obtener vacunas
        $bcg = Vacuna::where('nombre', 'BCG')->first();
        $hepatitisB = Vacuna::where('nombre', 'Hepatitis B')->first();
        $pentavalente = Vacuna::where('nombre', 'Pentavalente')->first();
        $polio = Vacuna::where('nombre', 'Polio')->first();
        $rotavirus = Vacuna::where('nombre', 'Rotavirus')->first();
        $neumococo = Vacuna::where('nombre', 'Neumococo')->first();
        $spr = Vacuna::where('nombre', 'SPR')->first();

        // Dosis para el esquema de niños
        $dosis = [
            // BCG - Al nacer
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $bcg->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 0,
                'es_refuerzo' => false,
                'observaciones' => 'Aplicar al recién nacido'
            ],

            // Hepatitis B - Al nacer, 2 y 6 meses
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $hepatitisB->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 0,
                'es_refuerzo' => false,
                'observaciones' => 'Primera dosis al nacer'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $hepatitisB->id,
                'numero_dosis' => 2,
                'edad_aplicacion' => 2,
                'es_refuerzo' => false,
                'observaciones' => 'Segunda dosis a los 2 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $hepatitisB->id,
                'numero_dosis' => 3,
                'edad_aplicacion' => 6,
                'es_refuerzo' => false,
                'observaciones' => 'Tercera dosis a los 6 meses'
            ],

            // Pentavalente - 2, 4 y 6 meses
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $pentavalente->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'es_refuerzo' => false,
                'observaciones' => 'Primera dosis a los 2 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $pentavalente->id,
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'es_refuerzo' => false,
                'observaciones' => 'Segunda dosis a los 4 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $pentavalente->id,
                'numero_dosis' => 3,
                'edad_aplicacion' => 6,
                'es_refuerzo' => false,
                'observaciones' => 'Tercera dosis a los 6 meses'
            ],

            // Polio - 2, 4 y 6 meses
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $polio->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'es_refuerzo' => false,
                'observaciones' => 'Primera dosis a los 2 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $polio->id,
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'es_refuerzo' => false,
                'observaciones' => 'Segunda dosis a los 4 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $polio->id,
                'numero_dosis' => 3,
                'edad_aplicacion' => 6,
                'es_refuerzo' => false,
                'observaciones' => 'Tercera dosis a los 6 meses'
            ],

            // Rotavirus - 2 y 4 meses
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $rotavirus->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'es_refuerzo' => false,
                'observaciones' => 'Primera dosis a los 2 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $rotavirus->id,
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'es_refuerzo' => false,
                'observaciones' => 'Segunda dosis a los 4 meses'
            ],

            // Neumococo - 2, 4 y 12 meses
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $neumococo->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 2,
                'es_refuerzo' => false,
                'observaciones' => 'Primera dosis a los 2 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $neumococo->id,
                'numero_dosis' => 2,
                'edad_aplicacion' => 4,
                'es_refuerzo' => false,
                'observaciones' => 'Segunda dosis a los 4 meses'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $neumococo->id,
                'numero_dosis' => 3,
                'edad_aplicacion' => 12,
                'es_refuerzo' => true,
                'observaciones' => 'Refuerzo al año'
            ],

            // SPR - 12 y 18 meses
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $spr->id,
                'numero_dosis' => 1,
                'edad_aplicacion' => 12,
                'es_refuerzo' => false,
                'observaciones' => 'Primera dosis al año'
            ],
            [
                'esquema_vacunacion_id' => $esquemaNinos->id,
                'vacuna_id' => $spr->id,
                'numero_dosis' => 2,
                'edad_aplicacion' => 18,
                'es_refuerzo' => true,
                'observaciones' => 'Refuerzo a los 18 meses'
            ]
        ];

        foreach ($dosis as $dosisData) {
            DosisVacuna::firstOrCreate(
                [
                    'esquema_vacunacion_id' => $dosisData['esquema_vacunacion_id'],
                    'vacuna_id' => $dosisData['vacuna_id'],
                    'numero_dosis' => $dosisData['numero_dosis']
                ],
                $dosisData
            );
        }
    }
}
