<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear roles si no existen
        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $coordinador = Role::firstOrCreate(['name' => 'Coordinador', 'guard_name' => 'web']);
        $docente = Role::firstOrCreate(['name' => 'Docente', 'guard_name' => 'web']);

        // Crear permisos básicos
        $permissions = [
            'Ver dashboard',
            'Ver perfil',
            'Editar perfil',
            'Ver evaluaciones',
            'Crear evaluaciones',
            'Editar evaluaciones',
            'Eliminar evaluaciones',
            'Ver usuarios',
            'Crear usuarios',
            'Editar usuarios',
            'Eliminar usuarios'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Asignar todos los permisos al administrador
        $admin->syncPermissions(Permission::all());

        // Asignar permisos al coordinador
        $coordinador->syncPermissions([
            'Ver dashboard',
            'Ver perfil',
            'Editar perfil',
            'Ver evaluaciones',
            'Crear evaluaciones',
            'Editar evaluaciones',
            'Ver usuarios'
        ]);

        // Asignar permisos al docente
        $docente->syncPermissions([
            'Ver dashboard',
            'Ver perfil',
            'Editar perfil',
            'Ver evaluaciones'
        ]);
    }
}