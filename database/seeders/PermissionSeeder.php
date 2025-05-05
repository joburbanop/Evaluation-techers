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

        // Permisos para asignaciones de tests
        Permission::create(['name' => 'Ver asignaciones', 'module' => 'evaluaciones', 'description' => 'Ver listado de asignaciones']);
        Permission::create(['name' => 'Crear asignaciones', 'module' => 'evaluaciones', 'description' => 'Crear nuevas asignaciones']);
        Permission::create(['name' => 'Editar asignaciones', 'module' => 'evaluaciones', 'description' => 'Editar asignaciones existentes']);
        Permission::create(['name' => 'Eliminar asignaciones', 'module' => 'evaluaciones', 'description' => 'Eliminar asignaciones']);
        Permission::create(['name' => 'Ver resultados', 'module' => 'evaluaciones', 'description' => 'Ver resultados de asignaciones']);

        // Permisos para gestión de permisos
        Permission::create(['name' => 'Ver permisos', 'module' => 'administracion', 'description' => 'Ver listado de permisos']);
        Permission::create(['name' => 'Crear permisos', 'module' => 'administracion', 'description' => 'Crear nuevos permisos']);
        Permission::create(['name' => 'Editar permisos', 'module' => 'administracion', 'description' => 'Editar permisos existentes']);
        Permission::create(['name' => 'Eliminar permiso', 'module' => 'administracion', 'description' => 'Eliminar permisos']);

        // Asignar todos los permisos al rol Administrador
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());
    }
} 