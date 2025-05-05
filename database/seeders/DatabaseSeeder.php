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

        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'document_type' => 'CC',
            'document_number' => '1234567890',
        ]);

        // Asignar rol de administrador
        $admin->assignRole('Administrador');

        // Ejecutar otros seeders
        $this->call([
            TestsSeeder::class,
        ]);
    }
}