<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrador = Role::create(['name' => 'ADMINISTRADOR']);
        $medico = Role::create(['name' => 'MEDICO']);
        $paciente = Role::create(['name' => 'PACIENTE']);
    }
}
