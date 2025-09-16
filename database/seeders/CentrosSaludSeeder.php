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
            // Provincia Huánuco (1001) - Huánuco
            [
                'codigo' => 'H1001',
                'nombre' => 'Hospital Hermilio Valdizán - Huánuco',
                'direccion' => 'Av. Mariscal Cáceres s/n, Huánuco',
                'telefono' => '062-421000',
                'email' => 'hospital.hv@minsa.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'II',
                'distrito_codigo' => '100101',
                'latitud' => -9.9300,
                'longitud' => -76.2420,
                'capacidad' => 120,
                'servicios' => json_encode(['Medicina General', 'Emergencias', 'Obstetricia', 'Pediatría', 'Laboratorio']),
                'director_medico' => 'Dr. Víctor Huamán',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],
            [
                'codigo' => 'CS1001A',
                'nombre' => 'Centro de Salud San Sebastián',
                'direccion' => 'Jr. Dos de Mayo 456, Huánuco',
                'telefono' => '062-422111',
                'email' => 'cs.sansebastian@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100101',
                'latitud' => -9.9285,
                'longitud' => -76.2410,
                'capacidad' => 80,
                'servicios' => json_encode(['Medicina General', 'Vacunación', 'Atención Prenatal']),
                'director_medico' => 'Dra. Carmen Quispe',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '17:00:00',
                'activo' => true
            ],

            // Provincia Ambo (1002)
            [
                'codigo' => 'H1002',
                'nombre' => 'Hospital de Apoyo Ambo',
                'direccion' => 'Plaza de Armas s/n, Ambo',
                'telefono' => '062-251200',
                'email' => 'hospital.ambo@minsa.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'II',
                'distrito_codigo' => '100201',
                'latitud' => -10.0210,
                'longitud' => -76.2150,
                'capacidad' => 60,
                'servicios' => json_encode(['Medicina General', 'Urgencias', 'Partos']),
                'director_medico' => 'Dr. Raúl Pérez',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],

            // Provincia Dos de Mayo (1003)
            [
                'codigo' => 'CS1003A',
                'nombre' => 'Centro de Salud La Unión',
                'direccion' => 'Jr. 28 de Julio, La Unión',
                'telefono' => '062-271000',
                'email' => 'cs.launion@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100301',
                'latitud' => -9.6970,
                'longitud' => -76.4570,
                'capacidad' => 30,
                'servicios' => json_encode(['Medicina General', 'Vacunación']),
                'director_medico' => 'Enf. Rosa Mendoza',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '16:00:00',
                'activo' => true
            ],

            // Provincia Huacaybamba (1004)
            [
                'codigo' => 'PS1004',
                'nombre' => 'Puesto de Salud Huacaybamba',
                'direccion' => 'Centro poblado, Huacaybamba',
                'telefono' => '062-281010',
                'email' => 'ps.huacaybamba@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-1',
                'distrito_codigo' => '100401',
                'latitud' => -9.9080,
                'longitud' => -76.8000,
                'capacidad' => 10,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación']),
                'director_medico' => 'Enf. Julio Tapia',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '14:00:00',
                'activo' => true
            ],

            // Provincia Huamalíes (1005)
            [
                'codigo' => 'H1005',
                'nombre' => 'Hospital de Apoyo Llata',
                'direccion' => 'Av. Centenario s/n, Llata',
                'telefono' => '062-311500',
                'email' => 'hospital.llata@minsa.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'II',
                'distrito_codigo' => '100501',
                'latitud' => -9.8580,
                'longitud' => -76.6000,
                'capacidad' => 40,
                'servicios' => json_encode(['Medicina General', 'Partos', 'Vacunación']),
                'director_medico' => 'Dr. Martín Huamaní',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],

            // Provincia Leoncio Prado (1006)
            [
                'codigo' => 'H1006',
                'nombre' => 'Hospital de Tingo María (Rupa-Rupa)',
                'direccion' => 'Jr. Fdo. Barreto 123, Rupa-Rupa',
                'telefono' => '062-331200',
                'email' => 'hospital.tingomaria@minsa.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'II',
                'distrito_codigo' => '100601',
                'latitud' => -9.2690,
                'longitud' => -75.9880,
                'capacidad' => 150,
                'servicios' => json_encode(['Medicina General', 'Emergencias', 'Cirugía Básica', 'Pediatría']),
                'director_medico' => 'Dra. Lidia Castro',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],

            // Provincia Marañón (1007)
            [
                'codigo' => 'CS1007A',
                'nombre' => 'Centro de Salud Huacrachuco',
                'direccion' => 'Plaza Principal, Huacrachuco',
                'telefono' => '062-341000',
                'email' => 'cs.huacrachuco@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100701',
                'latitud' => -9.1820,
                'longitud' => -76.6200,
                'capacidad' => 25,
                'servicios' => json_encode(['Medicina General', 'Vacunación']),
                'director_medico' => 'Dr. Rubén Suyon',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '16:00:00',
                'activo' => true
            ],

            // Provincia Pachitea (1008)
            [
                'codigo' => 'CS1008A',
                'nombre' => 'Centro de Salud Panao',
                'direccion' => 'Av. Central s/n, Panao',
                'telefono' => '062-351100',
                'email' => 'cs.panao@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100801',
                'latitud' => -9.8160,
                'longitud' => -75.9940,
                'capacidad' => 35,
                'servicios' => json_encode(['Medicina General', 'Atención Materno Infantil']),
                'director_medico' => 'Dra. Juliana Cruz',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '16:00:00',
                'activo' => true
            ],

            // Provincia Puerto Inca (1009)
            [
                'codigo' => 'PS1009',
                'nombre' => 'Puesto de Salud Puerto Inca',
                'direccion' => 'Av. Los Pescadores, Puerto Inca',
                'telefono' => '062-361500',
                'email' => 'ps.puertoinca@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-1',
                'distrito_codigo' => '100901',
                'latitud' => -9.1150,
                'longitud' => -74.9510,
                'capacidad' => 12,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación']),
                'director_medico' => 'Enf. Carmen Huaman',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '15:00:00',
                'activo' => true
            ],

            // Provincia Lauricocha (1010)
            [
                'codigo' => 'CS1010A',
                'nombre' => 'Centro de Salud Jesús',
                'direccion' => 'Plaza de Armas, Jesús',
                'telefono' => '062-371200',
                'email' => 'cs.jesus@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '101001',
                'latitud' => -9.7540,
                'longitud' => -76.5730,
                'capacidad' => 20,
                'servicios' => json_encode(['Medicina General', 'Vacunación']),
                'director_medico' => 'Dr. César León',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '16:00:00',
                'activo' => true
            ],

            // Provincia Yarowilca (1011)
            [
                'codigo' => 'PS1011',
                'nombre' => 'Puesto de Salud Chavinillo',
                'direccion' => 'Centro poblado, Chavinillo',
                'telefono' => '062-381000',
                'email' => 'ps.chavinillo@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-1',
                'distrito_codigo' => '101101',
                'latitud' => -9.6730,
                'longitud' => -76.4500,
                'capacidad' => 8,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación']),
                'director_medico' => 'Enf. Rosa Tapia',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '14:00:00',
                'activo' => true
            ],
        ];

        foreach ($centros as $centro) {
            CentroSalud::create($centro);
        }
    }
}
