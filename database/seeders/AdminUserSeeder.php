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
        // Crear usuario administrador Jonathan Burbano
        $admin = User::firstOrCreate(
            ['email' => 'jonathanc.burbano221@umariana.edu.co'],
            [
                'name'              => 'Jonathan Burbano',
                'email_verified_at' => Carbon::now(),
                'password'          => Hash::make('12345678'),
                'document_type'     => 'CC',
                'document_number'   => '100000001',
                'is_active'         => true,
                'phone'             => null,
                'departamento_id'   => null,
                'ciudad_id'         => null,
                'institution_id'    => 68,  // ← Cambiado a institution_id
                'facultad_id'       => 5,
                'programa_id'       => 39,
            ]
        );
        $admin->assignRole('Administrador');

        // Crear dos usuarios docentes
        $docentes = [
            [
                'name'            => 'Juan Pérez',
                'email'           => 'juan.perez@example.com',
                'document_type'   => 'CC',
                'document_number' => '100000002',
            ],
            [
                'name'            => 'María López',
                'email'           => 'maria.lopez@example.com',
                'document_type'   => 'CC',
                'document_number' => '100000003',
            ],
        ];

        foreach ($docentes as $docente) {
            $user = User::firstOrCreate(
                ['email' => $docente['email']],
                [
                    'name'              => $docente['name'],
                    'email_verified_at' => Carbon::now(),
                    'password'          => Hash::make('12345678'),
                    'document_type'     => $docente['document_type'],
                    'document_number'   => $docente['document_number'],
                    'is_active'         => true,
                    'phone'             => null,
                    'departamento_id'   => null,
                    'ciudad_id'         => null,
                    'institution_id'    => 68,  // ← Cambiado a institution_id
                    'facultad_id'       => 5,
                    'programa_id'       => 39,
                ]
            );
            $user->assignRole('Docente');
        }

        // Crear usuarios coordinadores
        $coordinadores = [
            [
                'name'            => 'Carlos Rodríguez',
                'email'           => 'carlos.rodriguez@example.com',
                'document_type'   => 'CC',
                'document_number' => '100000004',
            ],
            [
                'name'            => 'Ana Martínez',
                'email'           => 'ana.martinez@example.com',
                'document_type'   => 'CC',
                'document_number' => '100000005',
            ],
        ];

        foreach ($coordinadores as $coordinador) {
            $user = User::firstOrCreate(
                ['email' => $coordinador['email']],
                [
                    'name'              => $coordinador['name'],
                    'email_verified_at' => Carbon::now(),
                    'password'          => Hash::make('12345678'),
                    'document_type'     => $coordinador['document_type'],
                    'document_number'   => $coordinador['document_number'],
                    'is_active'         => true,
                    'phone'             => null,
                    'departamento_id'   => null,
                    'ciudad_id'         => null,
                    'institution_id'    => 68,  // ← Cambiado a institution_id
                    'facultad_id'       => 5,
                    'programa_id'       => 39,
                ]
            );
            $user->assignRole('Coordinador');
        }
    }
}