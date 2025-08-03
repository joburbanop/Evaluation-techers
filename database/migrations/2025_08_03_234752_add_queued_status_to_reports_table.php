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
        // Modificar el enum para incluir 'queued'
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'generating', 'completed', 'failed', 'queued') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir el enum a su estado original
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'generating', 'completed', 'failed') DEFAULT 'pending'");
    }
};
