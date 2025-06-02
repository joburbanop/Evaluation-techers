<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Factor;

class AreaFactorSeeder extends Seeder
{
    public function run(): void
    {
        // Primero inicializamos las áreas
        Area::initializeAreas();
        
        // Luego inicializamos los factores
        Factor::initializeFactors();
    }
} 