<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Elimina si ya existe para evitar duplicados
        User::where('email', 'jonathanc.burbano221@umariana.edu.co')->delete();

        $user = User::create([
            'name' => 'Jonathan Burbano',
            'email' => 'jonathanc.burbano221@umariana.edu.co',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'document_type' => 'CC',
            'document_number' => '100000001',
            'is_active' => true,
            'phone' => null,
            'departamento_id' => null,
            'ciudad_id' => null,
            'institution' => null,
        ]);

        // Asignar rol de admin
        $user->assignRole('Administrador');

        // Crear dos usuarios docentes
        $docentes = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@example.com',
                'document_type' => 'CC',
                'document_number' => '100000002',
            ],
            [
                'name' => 'María López',
                'email' => 'maria.lopez@example.com',
                'document_type' => 'CC',
                'document_number' => '100000003',
            ],
        ];

        foreach ($docentes as $docente) {
            User::create([
                'name' => $docente['name'],
                'email' => $docente['email'],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'document_type' => $docente['document_type'],
                'document_number' => $docente['document_number'],
                'is_active' => true,
                'phone' => null,
                'departamento_id' => null,
                'ciudad_id' => null,
                'institution' => null,
            ])->assignRole('Docente');
        }

        // Crear dos usuarios coordinadores
        $coordinadores = [
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@example.com',
                'document_type' => 'CC',
                'document_number' => '100000004',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@example.com',
                'document_type' => 'CC',
                'document_number' => '100000005',
            ],
        ];

        foreach ($coordinadores as $coordinador) {
            User::create([
                'name' => $coordinador['name'],
                'email' => $coordinador['email'],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'document_type' => $coordinador['document_type'],
                'document_number' => $coordinador['document_number'],
                'is_active' => true,
                'phone' => null,
                'departamento_id' => null,
                'ciudad_id' => null,
                'institution' => null,
            ])->assignRole('Coordinador');
        }
    }
} 