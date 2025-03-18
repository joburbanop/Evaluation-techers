<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear los roles
        $admin = Role::create(['name' => 'Administrador']);
        $coordinador = Role::create(['name' => 'Coordinador']);
        $docente = Role::create(['name' => 'Docente']);

          // Crear permisos
          $permissions = [
            // **Permisos de Usuarios**
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // **Permisos de Instituciones**
            'ver instituciones',
            'crear instituciones',
            'editar instituciones',
            'eliminar instituciones',

            // **Permisos de Tests**
            'ver tests',
            'crear tests',
            'editar tests',
            'eliminar tests',

            // **Permisos de Asignación de Tests**
            'asignar tests',
            'ver asignaciones de tests',

            // **Permisos de Roles y Permisos**
            'ver roles y permisos',
            'asignar roles y permisos',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


        // Asignar permisos a los roles
        $admin->givePermissionTo($permissions);
        $docente->givePermissionTo([]);
        $coordinador->givePermissionTo([]);

    }
}
