<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CheckRoles extends Command
{
    protected $signature = 'check:roles';
    protected $description = 'Check user roles in the system';

    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE ROLES ===');

        // Verificar roles existentes
        $roles = Role::all();
        $this->info('Roles en el sistema:');
        foreach ($roles as $role) {
            $this->line("- {$role->name}");
        }

        $this->newLine();

        // Verificar usuarios con roles
        $users = User::with('roles')->get();
        $this->info('Usuarios y sus roles:');

        foreach ($users as $user) {
            $userRoles = $user->roles->pluck('name')->join(', ');
            $rolesText = $userRoles ?: 'Sin roles asignados';
            $this->line("- {$user->name} ({$user->email}): {$rolesText}");
        }

        return 0;
    }
}
