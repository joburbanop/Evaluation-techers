<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Llave primaria
            $table->id();

            // Datos básicos
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Información documental (opcional)
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();

            // Llaves foráneas a tablas normalizadas
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->unsignedBigInteger('facultad_id')->nullable();
            $table->unsignedBigInteger('programa_id')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();

            // Otros campos del usuario
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            // Índices y restricciones de llave foránea
            $table->foreign('departamento_id')
                  ->references('id')
                  ->on('departamentos')
                  ->onDelete('set null');

            $table->foreign('ciudad_id')
                  ->references('id')
                  ->on('ciudades')
                  ->onDelete('set null');

            $table->foreign('institution_id')
                  ->references('id')
                  ->on('institutions')
                  ->onDelete('set null');

            $table->foreign('facultad_id')
                  ->references('id')
                  ->on('facultades')
                  ->onDelete('set null');

            $table->foreign('programa_id')
                  ->references('id')
                  ->on('programas')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};