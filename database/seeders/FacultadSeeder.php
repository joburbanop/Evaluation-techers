<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultadSeeder extends Seeder
{
    public function run(): void
    {
        $facultades = [
            ['nombre' => 'Facultad de Humanidades y Ciencias Sociales', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Facultad de Ciencias Contables, Económicas y Administrativas', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Facultad de Educación', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Facultad de Ciencias de la Salud', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Facultad de Ingeniería', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Direccion general', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Vicerrectoria academica', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Decanatura de Educación', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Decanatura de Ciencias Económicas, Contables y Administrativas', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Decanatura de Humanidades y Ciencias Sociales', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Decanatura de Ciencias de la Salud', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Dirección General', 'institution_id' => 68, 'created_at' => now(), 'updated_at' => now()],
            
        ];

        foreach ($facultades as $fac) {
            DB::table('facultades')->insert($fac);
        }
    }
}