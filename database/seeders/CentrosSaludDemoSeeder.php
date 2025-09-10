<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CentroSalud;

class CentrosSaludDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centrosSalud = [
            [
                'nombre' => 'Hospital Nacional Dos de Mayo',
                'codigo' => 'HNDDM001',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'III-1',
                'direccion' => 'Av. Grau 13, Cercado de Lima',
                'telefono' => '01-4265566',
                'email' => 'contacto@hnddm.gob.pe',
                'distrito_codigo' => '150101', // Lima
                'latitud' => -12.0464,
                'longitud' => -77.0428,
                'capacidad' => 500,
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'servicios' => ['Medicina General', 'Emergencias', 'Laboratorio', 'Rayos X', 'Farmacia', 'Vacunación'],
                'activo' => true,
            ],
            [
                'nombre' => 'Centro de Salud San Juan de Lurigancho',
                'codigo' => 'CS-SJL001',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-4',
                'direccion' => 'Av. Próceres de la Independencia 1234',
                'telefono' => '01-3456789',
                'email' => 'csalud.sjl@minsa.gob.pe',
                'distrito_codigo' => '150132', // San Juan de Lurigancho
                'latitud' => -11.9735,
                'longitud' => -77.0135,
                'capacidad' => 150,
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'servicios' => ['Medicina General', 'Pediatría', 'Ginecología', 'Laboratorio', 'Vacunación', 'Planificación Familiar'],
                'activo' => true,
            ],
            [
                'nombre' => 'Puesto de Salud Villa El Salvador',
                'codigo' => 'PS-VES001',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-2',
                'direccion' => 'Sector 1, Grupo 5, Mz A, Lote 1',
                'telefono' => '01-2876543',
                'email' => 'ps.ves@minsa.gob.pe',
                'distrito_codigo' => '150142', // Villa El Salvador
                'latitud' => -12.2172,
                'longitud' => -76.9394,
                'capacidad' => 80,
                'horario_inicio' => '08:00:00',
                'horario_fin' => '16:00:00',
                'servicios' => ['Medicina General', 'Vacunación', 'Planificación Familiar'],
                'activo' => true,
            ],
            [
                'nombre' => 'Hospital Nacional Edgardo Rebagliati Martins',
                'codigo' => 'HNERM001',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'III-2',
                'direccion' => 'Av. Edgardo Rebagliati 490, Jesús María',
                'telefono' => '01-2654321',
                'email' => 'contacto@rebagliati.gob.pe',
                'distrito_codigo' => '150113', // Jesús María
                'latitud' => -12.0732,
                'longitud' => -77.0567,
                'capacidad' => 800,
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'servicios' => ['Medicina General', 'Pediatría', 'Ginecología', 'Emergencias', 'Laboratorio', 'Rayos X', 'Farmacia', 'Vacunación', 'Salud Mental', 'Odontología'],
                'activo' => true,
            ],
            [
                'nombre' => 'Centro de Salud Miraflores',
                'codigo' => 'CS-MIR001',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'direccion' => 'Av. Arequipa 1550, Miraflores',
                'telefono' => '01-4567890',
                'email' => 'csalud.miraflores@minsa.gob.pe',
                'distrito_codigo' => '150122', // Miraflores
                'latitud' => -12.1203,
                'longitud' => -77.0307,
                'capacidad' => 120,
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'servicios' => ['Medicina General', 'Pediatría', 'Laboratorio', 'Vacunación', 'Nutrición'],
                'activo' => true,
            ],
            [
                'nombre' => 'Hospital Regional de Arequipa',
                'codigo' => 'HRA001',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'III-1',
                'direccion' => 'Av. Independencia s/n, Arequipa',
                'telefono' => '054-231515',
                'email' => 'contacto@regionalarequipa.gob.pe',
                'distrito_codigo' => '040101', // Arequipa
                'latitud' => -16.4040,
                'longitud' => -71.5197,
                'capacidad' => 400,
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'servicios' => ['Medicina General', 'Pediatría', 'Ginecología', 'Emergencias', 'Laboratorio', 'Rayos X', 'Farmacia', 'Vacunación'],
                'activo' => true,
            ],
        ];

        foreach ($centrosSalud as $centro) {
            CentroSalud::create($centro);
        }
    }
}
