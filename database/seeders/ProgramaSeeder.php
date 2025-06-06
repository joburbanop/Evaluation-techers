<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramaSeeder extends Seeder
{
    public function run(): void
    {
        $programas = [
            // === Facultad de Humanidades y Ciencias Sociales ===
            ['nombre' => 'Derecho',            'tipo' => 'Pregrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Trabajo Social',     'tipo' => 'Pregrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Comunicación Social','tipo' => 'Pregrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Psicología',         'tipo' => 'Pregrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Especialización en Familia',                     'tipo' => 'Posgrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Maestría en Derecho Público y Privado',          'tipo' => 'Posgrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Maestría en Gobernanza y Políticas Públicas',    'tipo' => 'Posgrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Maestría en Salud Mental (en convenio con CES)', 'tipo' => 'Posgrado', 'facultad_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            // === Facultad de Ciencias Contables, Económicas y Administrativas ===
            ['nombre' => 'Mercadeo',               'tipo' => 'Pregrado', 'facultad_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Contaduría Pública',      'tipo' => 'Pregrado', 'facultad_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Administración de Negocios Internacionales','tipo'=>'Pregrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Gerencia de Marketing Estratégico','tipo'=>'Posgrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Alta Gerencia',                 'tipo'=>'Posgrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Gerencia Tributaria',           'tipo'=>'Posgrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Gerencia Financiera',                  'tipo'=>'Posgrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Gerencia y Auditoría Tributaria',      'tipo'=>'Posgrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Administración',                       'tipo'=>'Posgrado','facultad_id'=>2,'created_at'=>now(),'updated_at'=>now()],

            // === Facultad de Educación ===
            ['nombre' => 'Licenciatura en Teología',            'tipo' => 'Pregrado', 'facultad_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Licenciatura en Literatura',          'tipo' => 'Pregrado', 'facultad_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Licenciatura en Matemáticas',         'tipo' => 'Pregrado', 'facultad_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Licenciatura en Educación Infantil',  'tipo' => 'Pregrado', 'facultad_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Licenciatura en Educación Básica Primaria','tipo'=>'Pregrado','facultad_id'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Gestión Educativa y Liderazgo','tipo'=>'Posgrado','facultad_id'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Pedagogía (virtual)',             'tipo'=>'Posgrado','facultad_id'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Doctorado en Pedagogía',                       'tipo'=>'Posgrado','facultad_id'=>3,'created_at'=>now(),'updated_at'=>now()],

            // === Facultad de Ciencias de la Salud ===
            ['nombre' => 'Enfermería',                                    'tipo'=>'Pregrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Terapia Ocupacional',                            'tipo'=>'Pregrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Fisioterapia',                                   'tipo'=>'Pregrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Nutrición y Dietética',                          'tipo'=>'Pregrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Tecnología en Regencia de Farmacia',             'tipo'=>'Tecnológico','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Tecnología en Radiodiagnóstico y Radioterapia',  'tipo'=>'Tecnológico','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Técnico Laboral en Auxiliar en Enfermería',      'tipo'=>'Técnico','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Enfermería Oncológica',       'tipo'=>'Posgrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Enfermería Materno Perinatal', 'tipo'=>'Posgrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Enfermería para el Cuidado del Paciente en Estado Crítico','tipo'=>'Posgrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Administración en Salud',             'tipo'=>'Posgrado','facultad_id'=>4,'created_at'=>now(),'updated_at'=>now()],

            // === Facultad de Ingeniería ===
            ['nombre' => 'Ingeniería Mecatrónica',       'tipo'=>'Pregrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Ingeniería Civil',             'tipo'=>'Pregrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Ingeniería de Sistemas',       'tipo'=>'Pregrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Ingeniería Ambiental',         'tipo'=>'Pregrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Ingeniería de Procesos',       'tipo'=>'Pregrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Especialización en Sistemas Integrados de Gestión','tipo'=>'Posgrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Diseño, Gestión y Optimización de Procesos','tipo'=>'Posgrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
            ['nombre' => 'Maestría en Ciencias Ambientales (con UT Pereira)','tipo'=>'Posgrado','facultad_id'=>5,'created_at'=>now(),'updated_at'=>now()],
        ];

        foreach ($programas as $p) {
            DB::table('programas')->insert($p);
        }
    }
}