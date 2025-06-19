<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Olvidar caché de permisos para que Spatie recargue bien
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definir todos los permisos
        $permisos = [
            // Usuarios
            ['name' => 'Ver usuarios',        'module' => 'administracion', 'description' => 'Ver listado de usuarios'],
            ['name' => 'Crear usuario',       'module' => 'administracion', 'description' => 'Crear nuevos usuarios'],
            ['name' => 'Editar usuario',      'module' => 'administracion', 'description' => 'Editar usuarios existentes'],
            ['name' => 'Eliminar usuario',    'module' => 'administracion', 'description' => 'Eliminar usuarios'],

            // Roles / permisos
            ['name' => 'Ver Gestion de roles', 'module' => 'administracion', 'description' => 'Ver gestión de roles'],
            ['name' => 'Crear permisos',       'module' => 'administracion', 'description' => 'Crear nuevos permisos'],
            ['name' => 'Editar permisos',      'module' => 'administracion', 'description' => 'Editar permisos existentes'],
            ['name' => 'Eliminar permiso',     'module' => 'administracion', 'description' => 'Eliminar permisos'],

            // Instituciones
            ['name' => 'Ver instituciones',    'module' => 'instituciones',  'description' => 'Ver listado de instituciones'],
            ['name' => 'Crear institucion',    'module' => 'instituciones',  'description' => 'Crear nuevas instituciones'],
            ['name' => 'Editar institucion',   'module' => 'instituciones',  'description' => 'Editar instituciones existentes'],
            ['name' => 'Eliminar institucion', 'module' => 'instituciones',  'description' => 'Eliminar instituciones'],

            // Tests
            ['name' => 'Ver tests',            'module' => 'evaluaciones',   'description' => 'Ver listado de tests'],
            ['name' => 'Crear test',           'module' => 'evaluaciones',   'description' => 'Crear nuevos tests'],
            ['name' => 'Editar test',          'module' => 'evaluaciones',   'description' => 'Editar tests existentes'],
            ['name' => 'Eliminar test',        'module' => 'evaluaciones',   'description' => 'Eliminar tests'],

            // Asignaciones de tests
            ['name' => 'Ver asignaciones',     'module' => 'evaluaciones',   'description' => 'Ver listado de asignaciones'],
            ['name' => 'Crear asignaciones',   'module' => 'evaluaciones',   'description' => 'Crear nuevas asignaciones'],
            ['name' => 'Editar asignaciones',  'module' => 'evaluaciones',   'description' => 'Editar asignaciones existentes'],
            ['name' => 'Eliminar asignaciones','module' => 'evaluaciones',   'description' => 'Eliminar asignaciones'],
            ['name' => 'Ver resultados',       'module' => 'evaluaciones',   'description' => 'Ver resultados de asignaciones'],
            ['name' => 'Realizar test',        'module' => 'evaluaciones',   'description' => 'Realizar tests asignados'],

            // Categorías
            ['name' => 'Ver categorías',       'module' => 'evaluaciones',   'description' => 'Ver listado de categorías'],
            ['name' => 'Crear categoría',      'module' => 'evaluaciones',   'description' => 'Crear nuevas categorías'],
            ['name' => 'Editar categoría',     'module' => 'evaluaciones',   'description' => 'Editar categorías existentes'],
            ['name' => 'Eliminar categoría',   'module' => 'evaluaciones',   'description' => 'Eliminar categorías'],
        ];

        // 3. Crear o recuperar cada permiso
        foreach ($permisos as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['module' => $perm['module'], 'description' => $perm['description']]
            );
        }

        // 4. Asignar todos los permisos al rol Administrador
        $adminRole = Role::firstOrCreate(
            ['name'       => 'Administrador', 'guard_name' => 'web'],
            ['guard_name' => 'web']
        );
        $coordinador = Role::firstOrCreate(['name' => 'Coordinador', 'guard_name' => 'web']);
        $docente = Role::firstOrCreate(['name' => 'Docente', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());
    }
}
