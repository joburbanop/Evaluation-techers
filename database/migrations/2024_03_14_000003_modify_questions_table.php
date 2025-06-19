<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Factor;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Primero agregamos las columnas sin la restricción de clave foránea
            $table->unsignedBigInteger('factor_id')->after('test_id')->nullable();
            $table->unsignedBigInteger('area_id')->after('factor_id')->nullable();
        });

        // Obtenemos el primer factor y área para asignarlos a las preguntas existentes
        $defaultFactor = Factor::first();
        $defaultArea = Area::first();

        if ($defaultFactor && $defaultArea) {
            DB::table('questions')->update([
                'factor_id' => $defaultFactor->id,
                'area_id' => $defaultArea->id
            ]);
        }

        // Ahora agregamos las restricciones de clave foránea
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('factor_id')->references('id')->on('factors')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            
            // Eliminamos la columna factor_digcomedu después de asegurarnos que los datos están migrados
            $table->dropColumn('factor_digcomedu');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('factor_digcomedu')->after('test_id');
            $table->dropForeign(['factor_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['factor_id', 'area_id']);
        });
    }
}; 