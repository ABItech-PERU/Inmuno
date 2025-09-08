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
        ])->assignRole('ADMINISTRADOR');

        User::factory()->create([
            'name' => 'MEDICO',
            'email' => 'medico@gmail.com',
        ])->assignRole('MEDICO');

        User::factory()->create([
            'name' => 'PACIENTE',
            'email' => 'paciente@gmail.com',
        ])->assignRole('PACIENTE');

        User::factory(10)->create();
    }
}
