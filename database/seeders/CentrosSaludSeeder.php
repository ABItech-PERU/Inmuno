<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CentroSalud;

class CentrosSaludSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centros = [
            [
                'codigo' => 'H001',
                'nombre' => 'Hospital Nacional Dos de Mayo',
                'direccion' => 'Av. Grau cuadra 13, Lima Cercado, Lima',
                'telefono' => '01-328-0028',
                'email' => 'info@h2demayo.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'III-1',
                'distrito_codigo' => '010101',
                'latitud' => -12.0553,
                'longitud' => -77.0453,
                'capacidad' => 500,
                'servicios' => json_encode(['Medicina General', 'Emergencias', 'Laboratorio', 'Rayos X']),
                'director_medico' => 'Dr. Carlos Mendoza',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],
            [
                'codigo' => 'CS001',
                'nombre' => 'Centro de Salud San Juan de Lurigancho',
                'direccion' => 'Av. Próceres de la Independencia 1234, SJL',
                'telefono' => '01-362-5555',
                'email' => 'cs.sjl@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '010102',
                'latitud' => -11.9739,
                'longitud' => -76.9974,
                'capacidad' => 150,
                'servicios' => json_encode(['Medicina General', 'Pediatría', 'Vacunación']),
                'director_medico' => 'Dra. Ana García',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],
            [
                'codigo' => 'CL001',
                'nombre' => 'Clínica Internacional',
                'direccion' => 'Av. Washington 1471, Lima',
                'telefono' => '01-619-6161',
                'email' => 'contacto@clinicainternacional.com.pe',
                'tipo' => 'Clínica',
                'nivel_atencion' => 'III-2',
                'distrito_codigo' => '010103',
                'latitud' => -12.0964,
                'longitud' => -77.0428,
                'capacidad' => 300,
                'servicios' => json_encode(['Medicina General', 'Emergencias', 'Laboratorio', 'Rayos X', 'Ginecología']),
                'director_medico' => 'Dr. Roberto Silva',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],
            [
                'codigo' => 'CS002',
                'nombre' => 'Centro de Salud Miraflores',
                'direccion' => 'Av. Arequipa 2415, Miraflores',
                'telefono' => '01-445-7890',
                'email' => 'cs.miraflores@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-4',
                'distrito_codigo' => '010104',
                'latitud' => -12.1192,
                'longitud' => -77.0298,
                'capacidad' => 120,
                'servicios' => json_encode(['Medicina General', 'Pediatría', 'Ginecología', 'Vacunación']),
                'director_medico' => 'Dr. Miguel Torres',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],
            [
                'codigo' => 'H002',
                'nombre' => 'Hospital Edgardo Rebagliati Martins',
                'direccion' => 'Av. Edgardo Rebagliati 490, Jesús María',
                'telefono' => '01-265-4901',
                'email' => 'informes@rebagliati.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'III-E',
                'distrito_codigo' => '010105',
                'latitud' => -12.0735,
                'longitud' => -77.0522,
                'capacidad' => 800,
                'servicios' => json_encode(['Medicina General', 'Emergencias', 'Laboratorio', 'Rayos X', 'Ginecología', 'Pediatría']),
                'director_medico' => 'Dra. Patricia Vargas',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],
            [
                'codigo' => 'CS003',
                'nombre' => 'Centro de Salud Villa El Salvador',
                'direccion' => 'Av. Revolución s/n, Villa El Salvador',
                'telefono' => '01-287-3456',
                'email' => 'cs.ves@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '010106',
                'latitud' => -12.2097,
                'longitud' => -76.9337,
                'capacidad' => 100,
                'servicios' => json_encode(['Medicina General', 'Vacunación', 'Planificación Familiar']),
                'director_medico' => 'Dr. José Ramírez',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '18:00:00',
                'activo' => true
            ],
            [
                'codigo' => 'PS001',
                'nombre' => 'Puesto de Salud San Antonio',
                'direccion' => 'Av. San Antonio 456, San Antonio',
                'telefono' => '01-475-2020',
                'email' => 'ps.sanantonio@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-1',
                'distrito_codigo' => '010107',
                'latitud' => -12.1058,
                'longitud' => -76.9917,
                'capacidad' => 50,
                'servicios' => json_encode(['Medicina General', 'Vacunación']),
                'director_medico' => 'Enf. María López',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '15:00:00',
                'activo' => true
            ],
            [
                'codigo' => 'CS004',
                'nombre' => 'Centro de Salud Callao',
                'direccion' => 'Av. Sáenz Peña 125, Callao',
                'telefono' => '01-429-8765',
                'email' => 'cs.callao@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '010108',
                'latitud' => -12.0667,
                'longitud' => -77.1167,
                'capacidad' => 180,
                'servicios' => json_encode(['Medicina General', 'Pediatría', 'Emergencias', 'Vacunación']),
                'director_medico' => 'Dr. Luis Fernández',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ]
        ];

        foreach ($centros as $centro) {
            CentroSalud::create($centro);
        }
    }
}
