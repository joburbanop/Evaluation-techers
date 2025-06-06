<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Partición profesional',
            'Tecnologias digitales',
            'Enseñanza y aprendizaje',
            'Evaluación',
            'Formacion de estudiantes',
            'Promocion de competencias digitales del alumnado',
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
