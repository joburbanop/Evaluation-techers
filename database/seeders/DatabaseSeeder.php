<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DepartamentoSeeder;
use Database\Seeders\CiudadSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TestsSeeder;                 // ✅ Primero los tests
use Database\Seeders\InstitutionSeeder;           // Luego las instituciones que dependen de tests
use Database\Seeders\FacultadSeeder;              // Luego facultades
use Database\Seeders\ProgramaSeeder;              // Luego programas
use Database\Seeders\CategorySeeder;
use Database\Seeders\AreaFactorSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\TestCompetencyLevelSeeder;
use Database\Seeders\TestAreaCompetencyLevelsSeeder;
use Database\Seeders\UsuariosFromExcelSeeder;
use Database\Seeders\TestInteligenciaArtificialSeeder;
use Database\Seeders\AssignTestsToTeachersSeeder;
use Database\Seeders\TestInteligenciaArtificialCompetencyLevelsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Geografía y permisos
            DepartamentoSeeder::class,
            CiudadSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,

            // 2. Tests y sus niveles de competencia
            TestsSeeder::class,
            TestInteligenciaArtificialSeeder::class,
            TestCompetencyLevelSeeder::class,
            TestInteligenciaArtificialCompetencyLevelsSeeder::class,
            TestAreaCompetencyLevelsSeeder::class,

            // 3. Entidades que referencian tests
            InstitutionSeeder::class,
            FacultadSeeder::class,
            ProgramaSeeder::class,

            // 4. Resto de datos
            CategorySeeder::class,
            AreaFactorSeeder::class,
            AdminUserSeeder::class,
            UsuariosFromExcelSeeder::class,
            
            // 5. Asignación de tests a docentes
            AssignTestsToTeachersSeeder::class,
        ]);
    }
}