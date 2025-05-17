<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_character');
            $table->string('departamento_domicilio');
            $table->string('municipio_domicilio');
            $table->integer('programas_vigentes')->nullable();
            $table->integer('active_programs')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_position')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};