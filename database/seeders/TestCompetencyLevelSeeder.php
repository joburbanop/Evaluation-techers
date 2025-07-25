<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestCompetencyLevel;

class TestCompetencyLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         TestCompetencyLevel::initializeLevels();
         TestCompetencyLevel::initializeLevelsIA();
    }
}
