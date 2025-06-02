<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders de ubicación
        $this->call([
            DepartamentoSeeder::class,
            CiudadSeeder::class,
        ]);

        // Ejecutar el seeder de permisos y roles
        $this->call(PermissionSeeder::class);

        // Ejecutar el seeder de roles
        $this->call(RoleSeeder::class);

        // Ejecutar otros seeders
        $this->call([
            CategorySeeder::class,
            AreaFactorSeeder::class,
            CompetencyLevelSeeder::class,
            TestsSeeder::class,
            AdminUserSeeder::class,
            InstitutionSeeder::class,
        ]);
    }
}