<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Competencia Pedagógica',
                'description' => 'Evaluación de competencias relacionadas con la pedagogía y la enseñanza.',
                'is_active' => true,
            ],
            [
                'name' => 'Competencia Comunicativa',
                'description' => 'Evaluación de competencias relacionadas con la comunicación efectiva.',
                'is_active' => true,
            ],
            [
                'name' => 'Competencia de Gestión',
                'description' => 'Evaluación de competencias relacionadas con la gestión y administración.',
                'is_active' => true,
            ],
            [
                'name' => 'Competencia Tecnológica',
                'description' => 'Evaluación de competencias relacionadas con el uso de tecnologías.',
                'is_active' => true,
            ],
            [
                'name' => 'Competencia Investigativa',
                'description' => 'Evaluación de competencias relacionadas con la investigación.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
