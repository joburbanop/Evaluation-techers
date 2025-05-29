<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'PARTICIPACION PROFESIONAL',
            'RECURSOS DIGITALES',
            'ENSEÑANZA Y APRENDIZAJE',
            'EVALUACIÓN',
            'EMPODERAMIENTO DEL ESTUDIANTE',
            'DESARROLLO DE LA COMPETENCIA DIGITAL DEL ESTUDIANTE',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                [
                    'description' => 'Área de evaluación basada en el marco DigCompEdu.',
                    'is_active' => true,
                ]
            );
        }
    }
}