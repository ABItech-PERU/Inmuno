<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'ADMINISTRADOR',
            'email' => 'admin@gmail.com',
            'se_registro' => 1,
        ])->assignRole('ADMINISTRADOR');

        User::factory()->create([
            'name' => 'MEDICO',
            'email' => 'medico@gmail.com',
            'se_registro' => 1,
        ])->assignRole('MEDICO');

        User::factory()->create([
            'name' => 'EPIFANIO',
            'apellidos' => 'FERNANDEZ VENTURA',
            'email' => 'paciente@gmail.com',
            'se_registro' => 0,
        ])->assignRole('PACIENTE');

        User::factory(10)->create();
    }
}
