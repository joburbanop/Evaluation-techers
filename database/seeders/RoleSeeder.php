<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $roles = [
            'Administrador',
            'Coordinador',
            'Docente'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear permisos básicos
        $permissions = [
            'Ver dashboard',
            'Gestionar usuarios',
            'Gestionar roles',
            'Gestionar permisos',
            'Gestionar tests',
            'Asignar tests',
            'Realizar test',
            'Ver resultados'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al rol de Administrador
        $adminRole = Role::findByName('Administrador');
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos específicos al rol de Docente
        $teacherRole = Role::findByName('Docente');
        $teacherRole->givePermissionTo([
            'Ver dashboard',
            'Realizar test',
            'Ver resultados'
        ]);

        // Asignar permisos específicos al rol de Coordinador
        $coordinatorRole = Role::findByName('Coordinador');
        $coordinatorRole->givePermissionTo([
            'Ver dashboard',
            'Gestionar tests',
            'Asignar tests',
            'Ver resultados'
        ]);
    }
}