<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Category;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Crear categorías si no existen
        $categorias = [
            'competencia_pedagogica' => 'Competencia Pedagógica',
            'competencia_comunicativa' => 'Competencia Comunicativa',
            'competencia_gestion' => 'Competencia de Gestión',
            'competencia_tecnologica' => 'Competencia Tecnológica',
            'competencia_investigativa' => 'Competencia Investigativa',
        ];

        foreach ($categorias as $slug => $nombre) {
            Category::firstOrCreate(
                ['name' => $nombre],
                [
                    'description' => "Evaluación de la {$nombre}",
                    'is_active' => true
                ]
            );
        }

        // Crear tests de ejemplo
        $tests = [
            [
                'name' => 'Evaluación de Competencias Pedagógicas Básicas',
                'description' => 'Test para evaluar las competencias pedagógicas fundamentales de los docentes',
                'category_id' => Category::where('name', 'Competencia Pedagógica')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Evaluación de Competencias Comunicativas',
                'description' => 'Test para evaluar las habilidades comunicativas y de expresión de los docentes',
                'category_id' => Category::where('name', 'Competencia Comunicativa')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Evaluación de Gestión Educativa',
                'description' => 'Test para evaluar las competencias en gestión y administración educativa',
                'category_id' => Category::where('name', 'Competencia de Gestión')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Evaluación de Competencias Tecnológicas',
                'description' => 'Test para evaluar el dominio de herramientas tecnológicas en la educación',
                'category_id' => Category::where('name', 'Competencia Tecnológica')->first()->id,
                'is_active' => true,
            ],
            [
                'name' => 'Evaluación de Competencias Investigativas',
                'description' => 'Test para evaluar las habilidades investigativas y de análisis de los docentes',
                'category_id' => Category::where('name', 'Competencia Investigativa')->first()->id,
                'is_active' => true,
            ],
        ];

        foreach ($tests as $test) {
            Test::firstOrCreate(
                ['name' => $test['name']],
                $test
            );
        }
    }
} 