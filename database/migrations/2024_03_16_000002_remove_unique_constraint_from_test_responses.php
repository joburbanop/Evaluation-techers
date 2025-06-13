<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_responses', function (Blueprint $table) {
            // Primero eliminamos las claves foráneas
            $table->dropForeign(['test_assignment_id']);
            $table->dropForeign(['question_id']);
            $table->dropForeign(['option_id']);
            $table->dropForeign(['user_id']);
            
            // Luego eliminamos el índice único
            $table->dropUnique('unique_assignment_question');
            
            // Volvemos a agregar las claves foráneas
            $table->foreign('test_assignment_id')->references('id')->on('test_assignments')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('test_responses', function (Blueprint $table) {
            // Primero eliminamos las claves foráneas
            $table->dropForeign(['test_assignment_id']);
            $table->dropForeign(['question_id']);
            $table->dropForeign(['option_id']);
            $table->dropForeign(['user_id']);
            
            // Agregamos el índice único
            $table->unique(['test_assignment_id', 'question_id'], 'unique_assignment_question');
            
            // Volvemos a agregar las claves foráneas
            $table->foreign('test_assignment_id')->references('id')->on('test_assignments')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}; 