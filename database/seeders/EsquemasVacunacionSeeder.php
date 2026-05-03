<?php

namespace Database\Seeders;

use App\Models\EsquemaVacunacion;
use Illuminate\Database\Seeder;

class EsquemasVacunacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear esquemas de vacunación según protocolo nacional peruano
        $esquemas = [
            // Esquemas para niños y niñas hasta los 5 años
            [
                'nombre' => 'Recién Nacidos',
                'descripcion' => 'Vacunas aplicadas al momento del nacimiento: BCG (protege contra tuberculosis) y primera dosis de Hepatitis B (protege el hígado)',
                'grupo_edad' => 'Recién nacidos',
                'edad_inicio' => 0,
                'edad_fin' => 0,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '2 Meses',
                'descripcion' => 'Primera serie de vacunas: Pentavalente (difteria, tétanos, tos ferina, hepatitis B, influenza tipo B), Polio (parálisis infantil), Rotavirus (diarreas severas) y Neumococo (neumonía)',
                'grupo_edad' => '2 meses',
                'edad_inicio' => 2,
                'edad_fin' => 2,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '4 Meses',
                'descripcion' => 'Segunda dosis de refuerzo: Pentavalente, Polio, Rotavirus y Neumococo para fortalecer la protección iniciada a los 2 meses',
                'grupo_edad' => '4 meses',
                'edad_inicio' => 4,
                'edad_fin' => 4,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '6 Meses',
                'descripcion' => 'Tercera dosis de Pentavalente y Polio para completar la serie básica, más primera dosis de Influenza (gripe) para protección respiratoria',
                'grupo_edad' => '6 meses',
                'edad_inicio' => 6,
                'edad_fin' => 6,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '7 Meses',
                'descripcion' => 'Segunda dosis de Influenza para completar la protección contra la gripe en bebés menores de 2 años',
                'grupo_edad' => '7 meses',
                'edad_inicio' => 7,
                'edad_fin' => 7,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '12 Meses',
                'descripcion' => 'Vacunas del primer año: SPR (sarampión, paperas, rubéola), tercera dosis de Neumococo, Varicela (evita las "viruelas locas") e Influenza anual',
                'grupo_edad' => '12 meses',
                'edad_inicio' => 12,
                'edad_fin' => 12,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '15 Meses',
                'descripcion' => 'Fiebre Amarilla (previene enfermedad grave transmitida por mosquitos) y Hepatitis A (protege el hígado contra infecciones alimentarias)',
                'grupo_edad' => '15 meses',
                'edad_inicio' => 15,
                'edad_fin' => 15,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '18 Meses',
                'descripcion' => 'Primeras vacunas de refuerzo: DPT (difteria, tos ferina, tétanos), Polio y segunda dosis de SPR para mantener alta la protección',
                'grupo_edad' => '18 meses',
                'edad_inicio' => 18,
                'edad_fin' => 18,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '2 Años',
                'descripcion' => 'Vacuna anual contra la Influenza para prevenir gripes y complicaciones respiratorias durante la temporada de mayor riesgo',
                'grupo_edad' => '2 años',
                'edad_inicio' => 24,
                'edad_fin' => 24,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '3 Años',
                'descripcion' => 'Vacuna anual contra la Influenza para mantener la protección contra gripes y prevenir complicaciones en niños pequeños',
                'grupo_edad' => '3 años',
                'edad_inicio' => 36,
                'edad_fin' => 36,
                'obligatorio' => true,
                'activo' => true
            ],
            [
                'nombre' => '4 Años',
                'descripcion' => 'Vacuna anual de Influenza y segundos refuerzos de DPT y Polio antes del ingreso escolar para garantizar protección completa',
                'grupo_edad' => '4 años',
                'edad_inicio' => 48,
                'edad_fin' => 48,
                'obligatorio' => true,
                'activo' => true
            ],

            // Esquemas para personas mayores de 5 años
            [
                'nombre' => 'Adolescentes VPH',
                'descripcion' => 'Vacuna contra el Virus del Papiloma Humano (VPH) para prevenir cáncer de cuello uterino, verrugas genitales y otros cánceres relacionados',
                'grupo_edad' => '9-13 años',
                'edad_inicio' => 108, // 9 años en meses
                'edad_fin' => 156,   // 13 años en meses
                'obligatorio' => true,
                'activo' => true
            ],

            [
                'nombre' => 'Jóvenes VPH',
                'descripcion' => 'Vacuna VPH para adolescentes de 14-18 años que no recibieron la vacuna en la edad recomendada (esquema de recuperación)',
                'grupo_edad' => '14-18 años',
                'edad_inicio' => 168, // 14 años en meses
                'edad_fin' => 216,    // 18 años en meses
                'obligatorio' => false,
                'activo' => false
            ],
            [
                'nombre' => 'Adultos',
                'descripcion' => 'Vacunas recomendadas para adultos: Fiebre Amarilla (para viajeros a zonas endémicas), Hepatitis B (trabajadores de salud) e Influenza anual (personas con enfermedades crónicas)',
                'grupo_edad' => 'Adultos',
                'edad_inicio' => 216, // 18 años en meses
                'edad_fin' => 720,    // 60 años en meses
                'obligatorio' => false,
                'activo' => false
            ],
            [
                'nombre' => 'Gestantes',
                'descripcion' => 'Vacunas esenciales durante el embarazo: Hepatitis B, DT (difteria y tétanos), Tdap (protege al bebé de tos ferina) e Influenza para protección madre-bebé',
                'grupo_edad' => 'Gestantes',
                'edad_inicio' => null,
                'edad_fin' => null,
                'obligatorio' => true,
                'activo' => false
            ],
            [
                'nombre' => 'Adultos Mayores',
                'descripcion' => 'Vacunas para adultos mayores de 60 años: Influenza anual (previene hospitalizaciones) y Neumococo (evita neumonía grave) debido a mayor riesgo de complicaciones',
                'grupo_edad' => 'Adultos mayores',
                'edad_inicio' => 720, // 60 años en meses
                'edad_fin' => null,
                'obligatorio' => true,
                'activo' => false
            ]
        ];

        // Crear los esquemas
        foreach ($esquemas as $esquemaData) {
            EsquemaVacunacion::firstOrCreate(
                ['nombre' => $esquemaData['nombre']],
                $esquemaData
            );
        }
    }
}
