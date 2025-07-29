<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remover el valor 'profesores_completados' del ENUM de la columna type
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('universidad', 'facultad', 'programa', 'profesor') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Agregar de vuelta el valor 'profesores_completados' al ENUM
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('universidad', 'facultad', 'programa', 'profesor', 'profesores_completados') NOT NULL");
    }
};
