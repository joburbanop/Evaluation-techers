<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos para reportes
        $permissions = [
            'Ver reportes' => 'Ver listado de reportes generados',
            'Generar reportes' => 'Crear nuevos reportes por facultad o programa',
            'Descargar reportes' => 'Descargar reportes en formato PDF',
            'Eliminar reportes' => 'Eliminar reportes propios o de otros usuarios',
            'Gestionar reportes' => 'Acceso completo a la gestión de reportes (solo administradores)',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'description' => $description,
                'module' => 'Reportes'
            ]);
        }

        // Asignar permisos a roles
        $adminRole = Role::where('name', 'Administrador')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'Ver reportes',
                'Generar reportes',
                'Descargar reportes',
                'Eliminar reportes',
                'Gestionar reportes'
            ]);
        }

        $coordinadorRole = Role::where('name', 'Coordinador')->first();
        if ($coordinadorRole) {
            $coordinadorRole->givePermissionTo([
                'Ver reportes',
                'Generar reportes',
                'Descargar reportes',
                'Eliminar reportes'
            ]);
        }

        $docenteRole = Role::where('name', 'Docente')->first();
        if ($docenteRole) {
            $docenteRole->givePermissionTo([
                'Ver reportes',
                'Descargar reportes'
            ]);
        }
    }
} 