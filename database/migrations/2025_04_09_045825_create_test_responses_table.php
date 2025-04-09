<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('option_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['test_assignment_id', 'question_id'], 'unique_assignment_question');
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_responses');
    }
};