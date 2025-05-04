<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;
use App\Models\Departamento;

class CiudadSeeder extends Seeder
{
    public function run()
    {
        $ciudadesPorDepartamento = [
            'Nariño' => [
                'Pasto',
                'Ipiales',
                'Tumaco',
                'Túquerres',
                'La Unión'
            ],
            'Valle del Cauca' => [
                'Cali',
                'Buenaventura',
                'Palmira',
                'Tuluá',
                'Cartago'
            ],
            'Antioquia' => [
                'Medellín',
                'Bello',
                'Itagüí',
                'Envigado',
                'Rionegro'
            ],
            // Agrega más ciudades según necesites
        ];

        foreach ($ciudadesPorDepartamento as $departamentoNombre => $ciudades) {
            $departamento = Departamento::where('name', $departamentoNombre)->first();
            if ($departamento) {
                foreach ($ciudades as $ciudadNombre) {
                    Ciudad::create([
                        'name' => $ciudadNombre,
                        'departamento_id' => $departamento->id
                    ]);
                }
            }
        }
    }
} 