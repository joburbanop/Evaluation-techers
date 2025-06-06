<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// (Asegúrate de importar todas estas clases)
use Database\Seeders\DepartamentoSeeder;
use Database\Seeders\CiudadSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\InstitutionSeeder;  // <-- debe ejecutarse antes que FacultadSeeder
use Database\Seeders\FacultadSeeder;      // <-- después de InstitutionSeeder
use Database\Seeders\ProgramaSeeder;      // <-- después de FacultadSeeder
use Database\Seeders\CategorySeeder;
use Database\Seeders\AreaFactorSeeder;
use Database\Seeders\TestsSeeder;
use Database\Seeders\CompetencyLevelSeeder;
use Database\Seeders\AdminUserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Primero los seeders de ubicación
        $this->call([
            DepartamentoSeeder::class,
            CiudadSeeder::class,
        ]);

        // 2) Luego permisos y roles (si aplican)
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);

        // 3) Ahora sí las instituciones (tabla padre de facultades)
        $this->call(InstitutionSeeder::class);

        // 4) Después las facultades (que necesitan que institutions ya tenga datos)
        $this->call(FacultadSeeder::class);

        // 5) Luego los programas (que dependen de facultades)
        $this->call(ProgramaSeeder::class);

        // 6) Y finalmente el resto de seeders “independientes” o que no rompan orden:
        $this->call([
            CategorySeeder::class,
            AreaFactorSeeder::class,
            TestsSeeder::class,
            CompetencyLevelSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}