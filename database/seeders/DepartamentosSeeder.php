<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            ['codigo_ubigeo' => '01', 'nombre' => 'Amazonas'],
            ['codigo_ubigeo' => '02', 'nombre' => 'Áncash'],
            ['codigo_ubigeo' => '03', 'nombre' => 'Apurímac'],
            ['codigo_ubigeo' => '04', 'nombre' => 'Arequipa'],
            ['codigo_ubigeo' => '05', 'nombre' => 'Ayacucho'],
            ['codigo_ubigeo' => '06', 'nombre' => 'Cajamarca'],
            ['codigo_ubigeo' => '07', 'nombre' => 'Callao'],
            ['codigo_ubigeo' => '08', 'nombre' => 'Cusco'],
            ['codigo_ubigeo' => '09', 'nombre' => 'Huancavelica'],
            ['codigo_ubigeo' => '10', 'nombre' => 'Huánuco'],
            ['codigo_ubigeo' => '11', 'nombre' => 'Ica'],
            ['codigo_ubigeo' => '12', 'nombre' => 'Junín'],
            ['codigo_ubigeo' => '13', 'nombre' => 'La Libertad'],
            ['codigo_ubigeo' => '14', 'nombre' => 'Lambayeque'],
            ['codigo_ubigeo' => '15', 'nombre' => 'Lima'],
            ['codigo_ubigeo' => '16', 'nombre' => 'Loreto'],
            ['codigo_ubigeo' => '17', 'nombre' => 'Madre de Dios'],
            ['codigo_ubigeo' => '18', 'nombre' => 'Moquegua'],
            ['codigo_ubigeo' => '19', 'nombre' => 'Pasco'],
            ['codigo_ubigeo' => '20', 'nombre' => 'Piura'],
            ['codigo_ubigeo' => '21', 'nombre' => 'Puno'],
            ['codigo_ubigeo' => '22', 'nombre' => 'San Martín'],
            ['codigo_ubigeo' => '23', 'nombre' => 'Tacna'],
            ['codigo_ubigeo' => '24', 'nombre' => 'Tumbes'],
            ['codigo_ubigeo' => '25', 'nombre' => 'Ucayali'],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
}
