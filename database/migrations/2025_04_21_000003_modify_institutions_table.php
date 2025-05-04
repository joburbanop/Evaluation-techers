<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Eliminar la columna city existente
            $table->dropColumn('city');
            
            // Agregar nuevas columnas
            $table->foreignId('ciudad_id')->constrained('ciudades');
            $table->enum('tipo', ['colegio', 'universidad'])->after('name');
            $table->string('website')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('city');  // Restaurar la columna city
            $table->dropForeign(['ciudad_id']);
            $table->dropColumn(['ciudad_id', 'tipo', 'website']);
        });
    }
}; 