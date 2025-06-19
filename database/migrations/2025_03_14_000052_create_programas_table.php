<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id();

            // Nombre del programa (ej.: Ingeniería Mecatrónica, Derecho, etc.)
            $table->string('nombre');
            
            // Tipo o nivel del programa: 'Pregrado', 'Posgrado', 'Técnico', 'Tecnológico'
            $table->string('tipo');

            // Clave foránea a facultades.id
            $table->unsignedBigInteger('facultad_id');

            $table->timestamps();

            // Índice y restricción de la FK
            $table->foreign('facultad_id')
                  ->references('id')
                  ->on('facultades')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropForeign(['facultad_id']);
        });
        Schema::dropIfExists('programas');
    }
};