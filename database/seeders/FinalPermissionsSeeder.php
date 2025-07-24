<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class FinalPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir todos los permisos necesarios
        $permissions = [
            // Permisos de administración (solo Admin)
            ['name' => 'Gestionar usuarios', 'module' => 'administracion'],
            ['name' => 'Gestionar roles', 'module' => 'administracion'],
            ['name' => 'Gestionar permisos', 'module' => 'administracion'],
            ['name' => 'Gestionar instituciones', 'module' => 'administracion'],
            ['name' => 'Gestionar facultades', 'module' => 'administracion'],
            ['name' => 'Gestionar programas', 'module' => 'administracion'],
            
            // Permisos de tests (Admin y Coordinador)
            ['name' => 'Gestionar tests', 'module' => 'evaluaciones'],
            ['name' => 'Crear test', 'module' => 'evaluaciones'],
            ['name' => 'Editar test', 'module' => 'evaluaciones'],
            ['name' => 'Eliminar test', 'module' => 'evaluaciones'],
            
            // Permisos de asignaciones (Admin y Coordinador - acceso completo)
            ['name' => 'Ver asignaciones', 'module' => 'evaluaciones'],
            ['name' => 'Crear asignaciones', 'module' => 'evaluaciones'],
            ['name' => 'Editar asignaciones', 'module' => 'evaluaciones'],
            ['name' => 'Eliminar asignaciones', 'module' => 'evaluaciones'],
            
            // Permisos de realizar test (Docente y Coordinador)
            ['name' => 'Realizar test', 'module' => 'evaluaciones'],
            ['name' => 'Ver resultados propios', 'module' => 'evaluaciones'],
            
            // Permisos de reportes
            ['name' => 'Ver reportes', 'module' => 'reportes'],
            ['name' => 'Generar reportes', 'module' => 'reportes'],
            ['name' => 'Descargar reportes', 'module' => 'reportes'],
            ['name' => 'Eliminar reportes', 'module' => 'reportes'],
            ['name' => 'Gestionar reportes', 'module' => 'reportes'], // Solo Admin
            
            // Permisos de categorías (solo Admin)
            ['name' => 'Gestionar categorías', 'module' => 'evaluaciones'],
        ];

        // Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'guard_name' => 'web'
            ], [
                'module' => $permission['module'],
                'description' => $permission['name']
            ]);
        }

        // Obtener roles
        $adminRole = Role::where('name', 'Administrador')->first();
        $coordinadorRole = Role::where('name', 'Coordinador')->first();
        $docenteRole = Role::where('name', 'Docente')->first();

        if (!$adminRole || !$coordinadorRole || !$docenteRole) {
            $this->command->error('Los roles no existen. Ejecuta RoleSeeder primero.');
            return;
        }

        // Permisos para ADMINISTRADOR (todos los permisos)
        $adminRole->syncPermissions(Permission::all());

        // Permisos para COORDINADOR (acceso completo a asignaciones y puede realizar tests)
        $coordinadorPermissions = [
            'Gestionar tests',
            'Crear test',
            'Editar test',
            'Eliminar test',
            'Ver asignaciones',
            'Crear asignaciones',
            'Editar asignaciones',
            'Eliminar asignaciones',
            'Realizar test', // El coordinador también puede realizar tests
            'Ver resultados propios', // Puede ver sus propios resultados
            'Ver reportes',
            'Generar reportes',
            'Descargar reportes',
            'Eliminar reportes',
        ];
        $coordinadorRole->syncPermissions($coordinadorPermissions);

        // Permisos para DOCENTE
        $docentePermissions = [
            'Realizar test',
            'Ver resultados propios',
        ];
        $docenteRole->syncPermissions($docentePermissions);

        $this->command->info('Permisos configurados correctamente:');
        $this->command->info('- Administrador: Todos los permisos');
        $this->command->info('- Coordinador: Gestión completa de tests y asignaciones, realizar tests, gestión de reportes');
        $this->command->info('- Docente: Realizar tests y ver resultados propios');
    }
} 