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
        Schema::create('test_area_competency_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Ej: Novato
            $table->string('code'); // Ej: A1
            $table->integer('min_score');
            $table->integer('max_score');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_area_competency_levels');
    }
};
