<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            VacunasSeeder::class,
            GruposEdadSeeder::class,
            EsquemasVacunacionSeeder::class,
            DosisVacunaSeeder::class, // Nuevo seeder para vincular vacunas con esquemas
            DepartamentosSeeder::class,
            ProvinciasSeeder::class,
            DistritosSeeder::class,
            CentrosSaludSeeder::class,
        ]);
    }
}
