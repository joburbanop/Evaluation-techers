<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['facultad', 'programa', 'institution', 'global']);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('entity_type')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->json('parameters')->nullable();
            $table->enum('status', ['pending', 'generating', 'completed', 'failed'])->default('pending');
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'entity_id', 'entity_type']);
            $table->index(['status', 'generated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
}; 