<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competency_levels', function (Blueprint $table) {
            $table->id();
                $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
                $table->string('name');
                $table->string('code');
                $table->integer('min_score');
                $table->integer('max_score');
                $table->text('description')->nullable();
                $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_levels');
    }
};
