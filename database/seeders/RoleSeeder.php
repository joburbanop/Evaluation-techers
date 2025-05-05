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


    }
}