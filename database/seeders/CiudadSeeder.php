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
            'Amazonas' => [
                'Leticia',
            ],
            'Antioquia' => [
                'Andes',
                'Apartadó',
                'Bello',
                'Caldas',
                'El Carmen de Viboral',
                'Envigado',
                'Marinilla',
                'Medellín',
                'Rionegro',
                'Sabaneta',
                'Santa Fé de Antioquia',
                'Santa Rosa de Osos',
                'Turbo',
            ],
            'Arauca' => [
                'Arauca',
            ],
            'Atlántico' => [
                'Barranquilla',
                'Malambo',
                'Soledad',
            ],
            'Bogotá, D.C.' => [
                'Bogotá, D.C.',
            ],
            'Bolívar' => [
                'Cartagena',
            ],
            'Boyacá' => [
                'Chiquinquirá',
                'Duitama',
                'Sogamoso',
                'Tunja',
            ],
            'Caldas' => [
                'Manizales',
            ],
            'Caquetá' => [
                'Florencia',
            ],
            'Casanare' => [
                'Yopal',
            ],
            'Cauca' => [
                'Popayán',
            ],
            'Cesar' => [
                'Valledupar',
            ],
            'Chocó' => [
                'Quibdó',
            ],
            'Córdoba' => [
                'Montería',
            ],
            'Cundinamarca' => [
                'Fusagasugá',
                'Girardot',
                'Soacha',
                'Zipaquirá',
            ],
            'Guainía' => [
                'Inírida',
            ],
            'Guaviare' => [
                'San José del Guaviare',
            ],
            'Huila' => [
                'Neiva',
            ],
            'La Guajira' => [
                'Riohacha',
            ],
            'Magdalena' => [
                'Santa Marta',
            ],
            'Meta' => [
                'Villavicencio',
            ],
            'Nariño' => [
                'Ipiales',
                'La Unión',
                'Pasto',
                'Tumaco',
                'Túquerres',
            ],
            'Norte de Santander' => [
                'Cúcuta',
            ],
            'Putumayo' => [
                'Mocoa',
            ],
            'Quindío' => [
                'Armenia',
            ],
            'Risaralda' => [
                'Dosquebradas',
                'Pereira',
            ],
            'Santander' => [
                'Bucaramanga',
                'Floridablanca',
                'Piedecuesta',
            ],
            'Sucre' => [
                'Sincelejo',
            ],
            'Tolima' => [
                'Ibagué',
                'Pitalito',
            ],
            'Valle del Cauca' => [
                'Buenaventura',
                'Cali',
                'Cartago',
                'Palmira',
                'Tuluá',
            ],
            'Vaupés' => [
                'Mitú',
            ],
            'Vichada' => [
                'Puerto Carreño',
            ],
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