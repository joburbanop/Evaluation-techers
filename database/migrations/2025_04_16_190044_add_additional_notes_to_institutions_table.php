<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalNotesToInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Agregar la columna 'additional_notes'
            $table->text('additional_notes')->nullable(); // Usamos 'text' porque puede contener más texto
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Eliminar la columna 'additional_notes'
            $table->dropColumn('additional_notes');
        });
    }
}
