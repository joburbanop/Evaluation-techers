<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestCompetencyLevel;

class TestCompetencyLevelSeeder extends Seeder
{
    public function run(): void
    {
        TestCompetencyLevel::initializeLevels();
    }
}