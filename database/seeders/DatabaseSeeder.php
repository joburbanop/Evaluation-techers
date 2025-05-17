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
        $this->call(PermissionSeeder::class,
           
        );


        // Ejecutar otros seeders
        $this->call([
            TestsSeeder::class,
            CategorySeeder::class,
        ]);
    }
}