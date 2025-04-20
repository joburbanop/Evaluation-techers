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
            
               
               
                //Permisos para el manejo de TestResource
                'Ver gestion Evaluaciones' => 'Ver gestion Evaluaciones',
                'Crear evaluaciones' => 'Crear evaluaciones',
                'Editar evaluaciones' => 'Editar evaluaciones',
                'Eliminar evaluaciones' =>  'Eliminar evaluaciones',

               
               //Permisos para el manejo de TestAssignmentResource
                'Ver asignaciones' => 'Ver asignaciones',
                'Crear asignaciones' => 'Crear asignaciones',
                'Editar asignaciones' => 'Editar asignaciones',
                'Eliminar asignaciones' => 'Eliminar asignaciones',
                'Ver resultados' => 'Ver resultados',

                //permisos para el manejo de RealizarTestResource
                'Realizar test' => 'Realizar test',
               
            ],
 
            'instituciones' => [
                'Crear institucion' => 'Crear Institución',
                'Editar institucion' => 'Editar Institución',
                'Eliminar institucion' => 'Eliminar Institución',
                'Ver institucion' => 'Ver Instituciones',
            ],
            
            
            'administracion' => [
                //permisos para el manejo de PermissionResource
                'Crear permisos' => 'Crear Permisos',
                'Editar permisos' => 'Editar Permisos',
                'Ver Gestion de roles' => 'Ver Permisos',


                //permisos para el manejo de UserResource 
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
                    'module' => $module,
                ]);
            }
        }

        // 4. Opcional: Asignar todos los permisos al Administrador
        $admin = Role::findByName('Administrador');
        $admin->givePermissionTo(Permission::all());
    }
}