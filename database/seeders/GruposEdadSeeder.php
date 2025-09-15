<?php

namespace Database\Seeders;

use App\Models\GrupoEdad;
use Illuminate\Database\Seeder;

class GruposEdadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gruposEdad = [
            [
                'nombre' => 'Recién Nacidos',
                'descripcion' => 'Grupo para bebés recién nacidos (0 meses)',
                'orden' => 1,
                'edad_inicio_meses' => 0,
                'edad_fin_meses' => 0,
                'activo' => true
            ],
            [
                'nombre' => '2 Meses',
                'descripcion' => 'Grupo para bebés de 2 meses de edad',
                'orden' => 2,
                'edad_inicio_meses' => 2,
                'edad_fin_meses' => 2,
                'activo' => true
            ],
            [
                'nombre' => '4 Meses',
                'descripcion' => 'Grupo para bebés de 4 meses de edad',
                'orden' => 3,
                'edad_inicio_meses' => 4,
                'edad_fin_meses' => 4,
                'activo' => true
            ],
            [
                'nombre' => '6 Meses',
                'descripcion' => 'Grupo para bebés de 6 meses de edad',
                'orden' => 4,
                'edad_inicio_meses' => 6,
                'edad_fin_meses' => 6,
                'activo' => true
            ],
            [
                'nombre' => '7 Meses',
                'descripcion' => 'Grupo para bebés de 7 meses de edad',
                'orden' => 5,
                'edad_inicio_meses' => 7,
                'edad_fin_meses' => 7,
                'activo' => true
            ],
            [
                'nombre' => '12 Meses',
                'descripcion' => 'Grupo para bebés de 12 meses (1 año)',
                'orden' => 6,
                'edad_inicio_meses' => 12,
                'edad_fin_meses' => 12,
                'activo' => true
            ],
            [
                'nombre' => '15 Meses',
                'descripcion' => 'Grupo para niños de 15 meses de edad',
                'orden' => 7,
                'edad_inicio_meses' => 15,
                'edad_fin_meses' => 15,
                'activo' => true
            ],
            [
                'nombre' => '18 Meses',
                'descripcion' => 'Grupo para niños de 18 meses de edad',
                'orden' => 8,
                'edad_inicio_meses' => 18,
                'edad_fin_meses' => 18,
                'activo' => true
            ],
            [
                'nombre' => '2 Años',
                'descripcion' => 'Grupo para niños de 2 años de edad',
                'orden' => 9,
                'edad_inicio_meses' => 24,
                'edad_fin_meses' => 24,
                'activo' => true
            ],
            [
                'nombre' => '3 Años',
                'descripcion' => 'Grupo para niños de 3 años de edad',
                'orden' => 10,
                'edad_inicio_meses' => 36,
                'edad_fin_meses' => 36,
                'activo' => true
            ],
            [
                'nombre' => '4 Años',
                'descripcion' => 'Grupo para niños de 4 años de edad',
                'orden' => 11,
                'edad_inicio_meses' => 48,
                'edad_fin_meses' => 48,
                'activo' => true
            ],
            [
                'nombre' => '9-13 Años',
                'descripcion' => 'Grupo para adolescentes de 9 a 13 años (vacunación VPH)',
                'orden' => 12,
                'edad_inicio_meses' => 108, // 9 años
                'edad_fin_meses' => 156,    // 13 años
                'activo' => true
            ],
            [
                'nombre' => '14-18 Años',
                'descripcion' => 'Grupo para jóvenes de 14 a 18 años',
                'orden' => 13,
                'edad_inicio_meses' => 168, // 14 años
                'edad_fin_meses' => 216,    // 18 años
                'activo' => true
            ],
            [
                'nombre' => 'Adultos',
                'descripcion' => 'Grupo para personas adultas de 18 a 59 años',
                'orden' => 14,
                'edad_inicio_meses' => 216, // 18 años
                'edad_fin_meses' => 708,    // 59 años
                'activo' => true
            ],
            [
                'nombre' => 'Gestantes',
                'descripcion' => 'Grupo especial para mujeres embarazadas (sin límite de edad específico)',
                'orden' => 15,
                'edad_inicio_meses' => null,
                'edad_fin_meses' => null,
                'activo' => true
            ],
            [
                'nombre' => 'Adultos Mayores',
                'descripcion' => 'Grupo para personas mayores de 60 años',
                'orden' => 16,
                'edad_inicio_meses' => 720, // 60 años
                'edad_fin_meses' => null,   // Sin límite superior
                'activo' => true
            ],

            // === GRUPOS FUTUROS (INACTIVOS) ===
            // Subdivisiones más específicas para bebés
            [
                'nombre' => '1 Mes',
                'descripcion' => 'Grupo para bebés de 1 mes de edad',
                'orden' => 17,
                'edad_inicio_meses' => 1,
                'edad_fin_meses' => 1,
                'activo' => false
            ],
            [
                'nombre' => '3 Meses',
                'descripcion' => 'Grupo para bebés de 3 meses de edad',
                'orden' => 18,
                'edad_inicio_meses' => 3,
                'edad_fin_meses' => 3,
                'activo' => false
            ],
            [
                'nombre' => '5 Meses',
                'descripcion' => 'Grupo para bebés de 5 meses de edad',
                'orden' => 19,
                'edad_inicio_meses' => 5,
                'edad_fin_meses' => 5,
                'activo' => false
            ],
            [
                'nombre' => '8 Meses',
                'descripcion' => 'Grupo para bebés de 8 meses de edad',
                'orden' => 20,
                'edad_inicio_meses' => 8,
                'edad_fin_meses' => 8,
                'activo' => false
            ],
            [
                'nombre' => '9 Meses',
                'descripcion' => 'Grupo para bebés de 9 meses de edad',
                'orden' => 21,
                'edad_inicio_meses' => 9,
                'edad_fin_meses' => 9,
                'activo' => false
            ],
            [
                'nombre' => '10 Meses',
                'descripcion' => 'Grupo para bebés de 10 meses de edad',
                'orden' => 22,
                'edad_inicio_meses' => 10,
                'edad_fin_meses' => 10,
                'activo' => false
            ],
            [
                'nombre' => '11 Meses',
                'descripcion' => 'Grupo para bebés de 11 meses de edad',
                'orden' => 23,
                'edad_inicio_meses' => 11,
                'edad_fin_meses' => 11,
                'activo' => false
            ],

            // Subdivisiones para niños pequeños
            [
                'nombre' => '13-17 Meses',
                'descripcion' => 'Grupo para niños de 13 a 17 meses',
                'orden' => 24,
                'edad_inicio_meses' => 13,
                'edad_fin_meses' => 17,
                'activo' => false
            ],
            [
                'nombre' => '19-23 Meses',
                'descripcion' => 'Grupo para niños de 19 a 23 meses',
                'orden' => 25,
                'edad_inicio_meses' => 19,
                'edad_fin_meses' => 23,
                'activo' => false
            ],

            // Años escolares específicos
            [
                'nombre' => '5 Años',
                'descripcion' => 'Grupo para niños de 5 años (ingreso escolar)',
                'orden' => 26,
                'edad_inicio_meses' => 60,
                'edad_fin_meses' => 71,
                'activo' => false
            ],
            [
                'nombre' => '6 Años',
                'descripcion' => 'Grupo para niños de 6 años (primer grado)',
                'orden' => 27,
                'edad_inicio_meses' => 72,
                'edad_fin_meses' => 83,
                'activo' => false
            ],
            [
                'nombre' => '7-8 Años',
                'descripcion' => 'Grupo para niños de 7 a 8 años (primaria temprana)',
                'orden' => 28,
                'edad_inicio_meses' => 84,
                'edad_fin_meses' => 107,
                'activo' => false
            ],

            // Subdivisiones de adolescentes
            [
                'nombre' => '9-11 Años',
                'descripcion' => 'Grupo para preadolescentes de 9 a 11 años',
                'orden' => 29,
                'edad_inicio_meses' => 108,
                'edad_fin_meses' => 143,
                'activo' => false
            ],
            [
                'nombre' => '12-13 Años',
                'descripcion' => 'Grupo para adolescentes tempranos de 12 a 13 años',
                'orden' => 30,
                'edad_inicio_meses' => 144,
                'edad_fin_meses' => 167,
                'activo' => false
            ],
            [
                'nombre' => '14-15 Años',
                'descripcion' => 'Grupo para adolescentes de 14 a 15 años',
                'orden' => 31,
                'edad_inicio_meses' => 168,
                'edad_fin_meses' => 191,
                'activo' => false
            ],
            [
                'nombre' => '16-17 Años',
                'descripcion' => 'Grupo para adolescentes de 16 a 17 años',
                'orden' => 32,
                'edad_inicio_meses' => 192,
                'edad_fin_meses' => 215,
                'activo' => false
            ],

            // Subdivisiones de adultos
            [
                'nombre' => '18-29 Años',
                'descripcion' => 'Grupo para adultos jóvenes de 18 a 29 años',
                'orden' => 33,
                'edad_inicio_meses' => 216,
                'edad_fin_meses' => 359,
                'activo' => false
            ],
            [
                'nombre' => '30-39 Años',
                'descripcion' => 'Grupo para adultos de 30 a 39 años',
                'orden' => 34,
                'edad_inicio_meses' => 360,
                'edad_fin_meses' => 479,
                'activo' => false
            ],
            [
                'nombre' => '40-49 Años',
                'descripcion' => 'Grupo para adultos de 40 a 49 años',
                'orden' => 35,
                'edad_inicio_meses' => 480,
                'edad_fin_meses' => 599,
                'activo' => false
            ],
            [
                'nombre' => '50-59 Años',
                'descripcion' => 'Grupo para adultos de 50 a 59 años',
                'orden' => 36,
                'edad_inicio_meses' => 600,
                'edad_fin_meses' => 719,
                'activo' => false
            ],

            // Subdivisiones de adultos mayores
            [
                'nombre' => '60-69 Años',
                'descripcion' => 'Grupo para adultos mayores de 60 a 69 años',
                'orden' => 37,
                'edad_inicio_meses' => 720,
                'edad_fin_meses' => 839,
                'activo' => false
            ],
            [
                'nombre' => '70-79 Años',
                'descripcion' => 'Grupo para adultos mayores de 70 a 79 años',
                'orden' => 38,
                'edad_inicio_meses' => 840,
                'edad_fin_meses' => 959,
                'activo' => false
            ],
            [
                'nombre' => '80+ Años',
                'descripcion' => 'Grupo para adultos mayores de 80 años en adelante',
                'orden' => 39,
                'edad_inicio_meses' => 960,
                'edad_fin_meses' => null,
                'activo' => false
            ],

            // Grupos especiales
            [
                'nombre' => 'Prematuros',
                'descripcion' => 'Grupo especial para bebés prematuros (esquemas modificados)',
                'orden' => 40,
                'edad_inicio_meses' => 0,
                'edad_fin_meses' => 24,
                'activo' => false
            ],
            [
                'nombre' => 'Inmunodeprimidos',
                'descripcion' => 'Grupo especial para personas con sistemas inmunes comprometidos',
                'orden' => 41,
                'edad_inicio_meses' => null,
                'edad_fin_meses' => null,
                'activo' => false
            ],
            [
                'nombre' => 'Personal Salud',
                'descripcion' => 'Grupo especial para trabajadores del sector salud',
                'orden' => 42,
                'edad_inicio_meses' => 216, // 18 años mínimo
                'edad_fin_meses' => null,
                'activo' => false
            ],
            [
                'nombre' => 'Viajeros',
                'descripcion' => 'Grupo especial para personas que viajan a zonas endémicas',
                'orden' => 43,
                'edad_inicio_meses' => null,
                'edad_fin_meses' => null,
                'activo' => false
            ],
            [
                'nombre' => 'Contactos COVID',
                'descripcion' => 'Grupo especial para contactos de casos COVID-19',
                'orden' => 44,
                'edad_inicio_meses' => null,
                'edad_fin_meses' => null,
                'activo' => false
            ],
            [
                'nombre' => 'Enfermedades Crónicas',
                'descripcion' => 'Grupo especial para personas con enfermedades crónicas (diabetes, hipertensión, etc.)',
                'orden' => 45,
                'edad_inicio_meses' => null,
                'edad_fin_meses' => null,
                'activo' => false
            ]
        ];

        foreach ($gruposEdad as $grupo) {
            GrupoEdad::firstOrCreate(
                ['nombre' => $grupo['nombre']],
                $grupo
            );
        }
    }
}
