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
                'nombre' => 'Hospital Nacional Dos de Mayo',
                'direccion' => 'Av. Grau cuadra 13, Lima Cercado, Lima',
                'telefono' => '01-328-0028',
                'email' => 'info@h2demayo.gob.pe',
                'tipo' => 'Hospital Nacional',
                'latitud' => -12.0553,
                'longitud' => -77.0453,
                'horarios' => 'Lunes a Domingo: 24 horas',
                'activo' => true
            ],
            [
                'nombre' => 'Centro de Salud San Juan de Lurigancho',
                'direccion' => 'Av. Próceres de la Independencia 1234, SJL',
                'telefono' => '01-362-5555',
                'email' => 'cs.sjl@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'latitud' => -11.9739,
                'longitud' => -76.9974,
                'horarios' => 'Lunes a Viernes: 7:00 AM - 7:00 PM, Sábados: 8:00 AM - 2:00 PM',
                'activo' => true
            ],
            [
                'nombre' => 'Clínica Internacional',
                'direccion' => 'Av. Washington 1471, Lima',
                'telefono' => '01-619-6161',
                'email' => 'contacto@clinicainternacional.com.pe',
                'tipo' => 'Clínica Privada',
                'latitud' => -12.0964,
                'longitud' => -77.0428,
                'horarios' => 'Lunes a Domingo: 24 horas',
                'activo' => true
            ],
            [
                'nombre' => 'Centro de Salud Miraflores',
                'direccion' => 'Av. Arequipa 2415, Miraflores',
                'telefono' => '01-445-7890',
                'email' => 'cs.miraflores@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'latitud' => -12.1192,
                'longitud' => -77.0298,
                'horarios' => 'Lunes a Viernes: 7:00 AM - 7:00 PM',
                'activo' => true
            ],
            [
                'nombre' => 'Hospital Edgardo Rebagliati Martins',
                'direccion' => 'Av. Edgardo Rebagliati 490, Jesús María',
                'telefono' => '01-265-4901',
                'email' => 'informes@rebagliati.gob.pe',
                'tipo' => 'Hospital EsSalud',
                'latitud' => -12.0735,
                'longitud' => -77.0522,
                'horarios' => 'Lunes a Domingo: 24 horas',
                'activo' => true
            ],
            [
                'nombre' => 'Centro de Salud Villa El Salvador',
                'direccion' => 'Av. Revolución s/n, Villa El Salvador',
                'telefono' => '01-287-3456',
                'email' => 'cs.ves@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'latitud' => -12.2097,
                'longitud' => -76.9337,
                'horarios' => 'Lunes a Sábado: 7:00 AM - 6:00 PM',
                'activo' => true
            ],
            [
                'nombre' => 'Policlínico San Borja',
                'direccion' => 'Av. Aviación 2765, San Borja',
                'telefono' => '01-475-2020',
                'email' => 'contacto@policlinicosanborja.pe',
                'tipo' => 'Policlínico',
                'latitud' => -12.1058,
                'longitud' => -76.9917,
                'horarios' => 'Lunes a Viernes: 7:00 AM - 9:00 PM, Sábados: 8:00 AM - 4:00 PM',
                'activo' => true
            ],
            [
                'nombre' => 'Centro de Salud Callao',
                'direccion' => 'Av. Sáenz Peña 125, Callao',
                'telefono' => '01-429-8765',
                'email' => 'cs.callao@minsa.gob.pe',
                'tipo' => 'Centro de Salud',
                'latitud' => -12.0667,
                'longitud' => -77.1167,
                'horarios' => 'Lunes a Viernes: 7:00 AM - 7:00 PM',
                'activo' => true
            ]
        ];

        foreach ($centros as $centro) {
            CentroSalud::create($centro);
        }
    }
}
