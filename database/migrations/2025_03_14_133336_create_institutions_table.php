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
            $table->string('name');               // Nombre de la institución
            $table->string('address')->nullable(); // Dirección
            $table->string('phone')->nullable();   // Teléfono
            $table->string('email')->nullable();   // Correo de contacto
            $table->string('contact_person')->nullable(); // Persona de contacto principal
            $table->date('foundation_date')->nullable();  // Fecha de fundación
            // Agrega otros campos que consideres necesarios
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};