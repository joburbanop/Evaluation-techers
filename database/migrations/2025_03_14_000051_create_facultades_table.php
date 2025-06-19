<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facultades', function (Blueprint $table) {
            $table->id();

            // Nombre de la facultad
            $table->string('nombre');

            // Clave foránea a institutions.id
            // (asegúrate de que exista la tabla 'institutions')
            $table->unsignedBigInteger('institution_id');

            $table->timestamps();

            // Índice y restricción de la FK
            $table->foreign('institution_id')
                  ->references('id')
                  ->on('institutions')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('facultades', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
        });
        Schema::dropIfExists('facultades');
    }
};