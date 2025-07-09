<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar la columna type para incluir los nuevos valores
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('universidad', 'facultad', 'programa', 'profesor') NOT NULL");
    }

    public function down(): void
    {
        // Revertir a los valores originales si es necesario
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('facultad', 'programa', 'institution', 'global') NOT NULL");
    }
}; 