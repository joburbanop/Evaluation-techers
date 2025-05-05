<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos para usuarios
        Permission::create(['name' => 'Ver usuarios', 'module' => 'administracion', 'description' => 'Ver listado de usuarios']);
        Permission::create(['name' => 'Crear usuario', 'module' => 'administracion', 'description' => 'Crear nuevos usuarios']);
        Permission::create(['name' => 'Editar usuario', 'module' => 'administracion', 'description' => 'Editar usuarios existentes']);
        Permission::create(['name' => 'Eliminar usuario', 'module' => 'administracion', 'description' => 'Eliminar usuarios']);

        // Permisos para roles
        Permission::create(['name' => 'Ver Gestion de roles', 'module' => 'administracion', 'description' => 'Ver gestión de roles']);
        Permission::create(['name' => 'Crear permisos', 'module' => 'administracion', 'description' => 'Crear nuevos permisos']);
        Permission::create(['name' => 'Editar permisos', 'module' => 'administracion', 'description' => 'Editar permisos existentes']);
        Permission::create(['name' => 'Eliminar permiso', 'module' => 'administracion', 'description' => 'Eliminar permisos']);

        // Permisos para instituciones
        Permission::create(['name' => 'Ver instituciones', 'module' => 'instituciones', 'description' => 'Ver listado de instituciones']);
        Permission::create(['name' => 'Crear institucion', 'module' => 'instituciones', 'description' => 'Crear nuevas instituciones']);
        Permission::create(['name' => 'Editar institucion', 'module' => 'instituciones', 'description' => 'Editar instituciones existentes']);
        Permission::create(['name' => 'Eliminar institucion', 'module' => 'instituciones', 'description' => 'Eliminar instituciones']);

        // Permisos para tests
        Permission::create(['name' => 'Ver tests', 'module' => 'evaluaciones', 'description' => 'Ver listado de tests']);
        Permission::create(['name' => 'Crear test', 'module' => 'evaluaciones', 'description' => 'Crear nuevos tests']);
        Permission::create(['name' => 'Editar test', 'module' => 'evaluaciones', 'description' => 'Editar tests existentes']);
        Permission::create(['name' => 'Eliminar test', 'module' => 'evaluaciones', 'description' => 'Eliminar tests']);
        Permission::create(['name' => 'Asignar test', 'module' => 'evaluaciones', 'description' => 'Asignar tests a usuarios']);
        Permission::create(['name' => 'Realizar test', 'module' => 'evaluaciones', 'description' => 'Realizar tests asignados']);

        // Crear rol de Administrador y asignar todos los permisos
        $adminRole = Role::create(['name' => 'Administrador']);
        $adminRole->givePermissionTo(Permission::all());

        // Crear rol de Coordinador con permisos específicos
        $coordinadorRole = Role::create(['name' => 'Coordinador']);
        $coordinadorRole->givePermissionTo([
            'Ver usuarios', 'Ver instituciones', 'Ver tests',
            'Crear test', 'Editar test', 'Asignar test'
        ]);

        // Crear rol de Docente con permisos básicos
        $docenteRole = Role::create(['name' => 'Docente']);
        $docenteRole->givePermissionTo([
            'Ver tests', 'Realizar test'
        ]);
    }
} 