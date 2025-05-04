<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        $departamentos = [
            ['name' => 'Amazonas'],
            ['name' => 'Antioquia'],
            ['name' => 'Arauca'],
            ['name' => 'Atlántico'],
            ['name' => 'Bolívar'],
            ['name' => 'Boyacá'],
            ['name' => 'Caldas'],
            ['name' => 'Caquetá'],
            ['name' => 'Casanare'],
            ['name' => 'Cauca'],
            ['name' => 'Cesar'],
            ['name' => 'Chocó'],
            ['name' => 'Córdoba'],
            ['name' => 'Cundinamarca'],
            ['name' => 'Guainía'],
            ['name' => 'Guaviare'],
            ['name' => 'Huila'],
            ['name' => 'La Guajira'],
            ['name' => 'Magdalena'],
            ['name' => 'Meta'],
            ['name' => 'Nariño'],
            ['name' => 'Norte de Santander'],
            ['name' => 'Putumayo'],
            ['name' => 'Quindío'],
            ['name' => 'Risaralda'],
            ['name' => 'San Andrés y Providencia'],
            ['name' => 'Santander'],
            ['name' => 'Sucre'],
            ['name' => 'Tolima'],
            ['name' => 'Valle del Cauca'],
            ['name' => 'Vaupés'],
            ['name' => 'Vichada'],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
} 