<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_competency_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->integer('min_score');
            $table->integer('max_score');
            $table->text('description')->nullable();
            $table->timestamps();

            // Agregar índices
            $table->index(['test_id', 'code']);
            $table->index(['min_score', 'max_score']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_competency_levels');
    }
};
