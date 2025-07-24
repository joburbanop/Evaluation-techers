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
        // Agregar el nuevo valor 'profesores_completados' al ENUM de la columna type
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('universidad', 'facultad', 'programa', 'profesor', 'profesores_completados') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover el valor 'profesores_completados' del ENUM
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('universidad', 'facultad', 'programa', 'profesor') NOT NULL");
    }
};
