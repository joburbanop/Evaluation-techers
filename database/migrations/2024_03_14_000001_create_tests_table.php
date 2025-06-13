<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Agregar índices
            $table->index('name');
            $table->index('is_active');
            $table->index(['created_at', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
}; 