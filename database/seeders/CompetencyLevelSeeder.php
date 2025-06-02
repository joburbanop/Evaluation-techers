<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompetencyLevel;

class CompetencyLevelSeeder extends Seeder
{
    public function run(): void
    {
        CompetencyLevel::initializeLevels();
    }
} 