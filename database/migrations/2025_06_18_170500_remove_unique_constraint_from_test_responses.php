<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_response_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_response_id')->constrained('test_responses')->onDelete('cascade');
            $table->foreignId('option_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['test_response_id', 'option_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_response_options');
    }
}; 