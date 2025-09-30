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
            // Código 760 - Puesto de Salud La Esperanza
            [
                'codigo' => '760',
                'nombre' => 'PUESTO DE SALUD LA ESPERANZA',
                'direccion' => 'C. San Juan Bosco 131, Amarilis 10000',
                'telefono' => '953577897',
                'email' => 'ps.laesperanza@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100102',
                'latitud' => -9.8962497,
                'longitud' => -76.2196108,
                'capacidad' => 15,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación', 'Control de Crecimiento', 'Atención Prenatal Básica']),
                'director_medico' => 'Enf. María González',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '14:00:00',
                'activo' => true
            ],

            // Código 754 - Hospital Hermilio Valdizán
            [
                'codigo' => '754',
                'nombre' => 'HOSPITAL HERMILIO VALDIZAN',
                'direccion' => 'Jr. Hermilio Valdizán 950',
                'telefono' => '999954310',
                'email' => 'hospital.hvaldizan@minsa.gob.pe',
                'tipo' => 'Hospital',
                'nivel_atencion' => 'II-2',
                'distrito_codigo' => '100101',
                'latitud' => -9.9277235,
                'longitud' => -76.2364539,
                'capacidad' => 250,
                'servicios' => json_encode(['Medicina General', 'Emergencias', 'Cirugía', 'Pediatría', 'Ginecología', 'Obstetricia', 'Laboratorio', 'Radiología', 'UCI', 'Hospitalización']),
                'director_medico' => 'Dr. Víctor Huamán Rojas',
                'horario_inicio' => '00:00:00',
                'horario_fin' => '23:59:59',
                'activo' => true
            ],

            // Código 755 - Centro de Salud Perú-Corea
            [
                'codigo' => '755',
                'nombre' => 'CENTRO DE SALUD PERU-COREA',
                'direccion' => '10001, Amarilis 10001',
                'telefono' => '62519676',
                'email' => 'cs.perucorea@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-4',
                'distrito_codigo' => '100102',
                'latitud' => -9.9184573,
                'longitud' => -76.2271384,
                'capacidad' => 80,
                'servicios' => json_encode(['Medicina General', 'Odontología', 'Obstetricia', 'Pediatría', 'Enfermería', 'Laboratorio', 'Farmacia', 'Emergencias']),
                'director_medico' => 'Dr. Carlos Mendoza Ríos',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],

            // Código 757 - Centro de Salud Potracancha
            [
                'codigo' => '757',
                'nombre' => 'CENTRO DE SALUD POTRACANCHA',
                'direccion' => 'Jr. San Diego Cudra 2 s/n - Cayhuayna',
                'telefono' => '62516639',
                'email' => 'cs.potracancha@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100111',
                'latitud' => -9.9584152,
                'longitud' => -76.2491506,
                'capacidad' => 50,
                'servicios' => json_encode(['Medicina General', 'Enfermería', 'Obstetricia', 'Odontología', 'Vacunación', 'Farmacia', 'Laboratorio']),
                'director_medico' => 'Dra. Patricia Rojas León',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],

            // Código 759 - Centro de Salud Cayran
            [
                'codigo' => '759',
                'nombre' => 'CENTRO DE SALUD CAYRAN',
                'direccion' => 'Jr. San Pedro N° 300, San Francisco de Cayran',
                'telefono' => '955897279',
                'email' => 'cs.cayran@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100107',
                'latitud' => -9.9808566,
                'longitud' => -76.2843916,
                'capacidad' => 45,
                'servicios' => json_encode(['Medicina General', 'Enfermería', 'Obstetricia', 'Odontología', 'Vacunación', 'Farmacia']),
                'director_medico' => 'Dr. Jorge Espinoza Torres',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],

            // Código 762 - Puesto de Salud Paucar
            [
                'codigo' => '762',
                'nombre' => 'PUESTO DE SALUD PAUCAR',
                'direccion' => '3RSG+FP, Centro Poblado de Malconga 10000',
                'telefono' => '975758245',
                'email' => 'ps.paucar@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100102',
                'latitud' => -9.9041248,
                'longitud' => -76.173279,
                'capacidad' => 12,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación', 'Atención Prenatal Básica', 'Control de Niño Sano']),
                'director_medico' => 'Enf. Rosa Díaz Pérez',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '14:00:00',
                'activo' => true
            ],

            // Código 763 - Puesto de Salud de Llicua
            [
                'codigo' => '763',
                'nombre' => 'PUESTO DE SALUD DE LLICUA',
                'direccion' => '3QC9+V92, Unnamed Road, Amarilis 10002',
                'telefono' => '947194495',
                'email' => 'ps.llicua@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100102',
                'latitud' => -9.927852,
                'longitud' => -76.231621,
                'capacidad' => 10,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación', 'Control de Niño Sano', 'Atención Prenatal']),
                'director_medico' => 'Enf. Luis Vargas Silva',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '14:00:00',
                'activo' => true
            ],

            // Código 786 - Puesto de Salud Colpa Baja
            [
                'codigo' => '786',
                'nombre' => 'PUESTO DE SALUD COLPA BAJA',
                'direccion' => '4Q6F+FM6, Huánuco 10000',
                'telefono' => '936098738',
                'email' => 'ps.colpabaja@minsa.gob.pe',
                'tipo' => 'Puesto de Salud',
                'nivel_atencion' => 'I-2',
                'distrito_codigo' => '100102',
                'latitud' => -9.8887738,
                'longitud' => -76.2258048,
                'capacidad' => 12,
                'servicios' => json_encode(['Medicina Básica', 'Vacunación', 'Atención Prenatal', 'Control CRED']),
                'director_medico' => 'Enf. Carmen Ramírez Huamán',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '14:00:00',
                'activo' => true
            ],

            // Código 787 - Centro de Salud Las Moras
            [
                'codigo' => '787',
                'nombre' => 'CENTRO DE SALUD LAS MORAS',
                'direccion' => 'Jr. Raymondi Mz. M-10, Las Moras',
                'telefono' => '920152888',
                'email' => 'cs.lasmoras@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100101',
                'latitud' => -9.9153027,
                'longitud' => -76.2382847,
                'capacidad' => 50,
                'servicios' => json_encode(['Medicina General', 'Odontología', 'Enfermería', 'Obstetricia', 'Laboratorio', 'Farmacia', 'Psicología']),
                'director_medico' => 'Dr. Roberto Silva Mendoza',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],

            // Código 28613 - Centro de Salud Mental Comunitario Esperanza
            [
                'codigo' => '28613',
                'nombre' => 'CENTRO DE SALUD MENTAL COMUNITARIO ESPERANZA',
                'direccion' => 'Av. Circunvalación N° 565, Piso 1, Urb. Arbulú Pineda - Moras',
                'telefono' => '952021574',
                'email' => 'csmc.esperanza@minsa.gob.pe',
                'tipo' => 'Centro Médico Especializado',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100101',
                'latitud' => -9.917082,
                'longitud' => -72.236359,
                'capacidad' => 60,
                'servicios' => json_encode(['Psiquiatría', 'Psicología', 'Terapia Individual', 'Terapia Grupal', 'Terapia Familiar', 'Consejería', 'Rehabilitación Mental']),
                'director_medico' => 'Dr. Fernando Arbulú Pineda',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '20:00:00',
                'activo' => true
            ],

            // Código 28875 - Centro de Salud Aparicio Pomares
            [
                'codigo' => '28875',
                'nombre' => 'CENTRO DE SALUD APARICIO POMARES',
                'direccion' => 'Prolongación Junín s/n, entre Jr. Micaela Bastidas e Independencia',
                'telefono' => '62513909',
                'email' => 'cs.apariciopomares@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100101',
                'latitud' => -9.9207648,
                'longitud' => -76.2410843,
                'capacidad' => 60,
                'servicios' => json_encode(['Medicina General', 'Pediatría', 'Ginecología', 'Obstetricia', 'Enfermería', 'Laboratorio', 'Farmacia', 'Odontología']),
                'director_medico' => 'Dra. Sandra López Rojas',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],

            // Código 27821 - Centro de Salud Amarilis
            [
                'codigo' => '27821',
                'nombre' => 'CENTRO DE SALUD AMARILIS',
                'direccion' => 'Jr. Miguel Grau 102, Amarilis 10000',
                'telefono' => '972536762',
                'email' => 'cs.amarilis@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'nivel_atencion' => 'I-3',
                'distrito_codigo' => '100102',
                'latitud' => -9.9392906,
                'longitud' => -76.2402604,
                'capacidad' => 55,
                'servicios' => json_encode(['Medicina General', 'Odontología', 'Enfermería', 'Obstetricia', 'Psicología', 'Laboratorio', 'Farmacia']),
                'director_medico' => 'Dr. Miguel Torres Espinoza',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '19:00:00',
                'activo' => true
            ],
        ];

        foreach ($centros as $centro) {
            CentroSalud::create($centro);
        }
    }
}
