<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
 
        Role::firstOrCreate(
            ['name' => 'Administrador', 'guard_name' => 'web'],
            ['description' => 'Acceso total al sistema']
        );
        
        Role::firstOrCreate(
            ['name' => 'Coordinador', 'guard_name' => 'web'],
            ['description' => 'Gestiona evaluaciones y asignaciones']
        );
        
        Role::firstOrCreate(
            ['name' => 'Docente', 'guard_name' => 'web'],
            ['description' => 'Realiza tests y ve resultados']
        );
        

        // 2. Definir permisos por módulo (basado en tu captura)
        $modules = [
            'evaluaciones' => [
                'Realizar test' => 'Realizar Test',
                'Asignar evaluaciones' => 'Gestionar Asignaciones de Evaluaciones',
                'Crear evaluaciones' => 'Crear Evaluaciones',
            ],
 
            'instituciones' => [
                'Crear institucion' => 'Crear Institución',
                'Editar institucion' => 'Editar Institución',
                'Eliminar institucion' => 'Eliminar Institución',
                'Ver institucion' => 'Ver Instituciones',
            ],
            'permisos' => [
                'Crear permiso' => 'Crear Permiso',
                'Editar permiso' => 'Editar Permiso',
                'Eliminar permiso' => 'Eliminar Permiso',
                'Ver permisos' => 'Ver Permisos',
            ],
            'tests' => [
                'Crear test' => 'Crear Test',
                'Editar test' => 'Editar Test',
                'Eliminar test' => 'Eliminar Test',
                'Ver tests' => 'Ver Tests',
            ],
            'asignacion_tests' => [
                'Asignar test' => 'Asignar Test',
                'Ver asignaciones' => 'Ver Asignaciones',
                'Editar asignaciones' => 'Editar Asignaciones',
                'Eliminar asignaciones' => 'Eliminar Asignaciones',
            ],
            'usuarios' => [
                'Crear usuario' => 'Crear Usuario',
                'Editar usuario' => 'Editar Usuario',
                'Eliminar usuario' => 'Eliminar Usuario',
                'Ver usuarios' => 'Ver Usuarios',
            ],
        ];
        
        // 3. Crear permisos en la base de datos
        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $key => $description) {
                Permission::firstOrCreate([
                    'name' => $key,
                    'description' => $description,
                    'module' => $module, // Agrupa en Filament
                ]);
            }
        }

        // 4. Opcional: Asignar todos los permisos al Administrador
        $admin = Role::findByName('Administrador');
        $admin->givePermissionTo(Permission::all());
    }
}