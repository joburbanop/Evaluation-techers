<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_id');
            $table->date('assigned_date');
            $table->time('assigned_time');
            $table->dateTime('assigned_at');
            $table->date('due_date');
            $table->time('due_time');
            $table->dateTime('due_at');
            $table->text('instructions')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
        });
       
    }

    public function down(): void
    {
        Schema::dropIfExists('test_assignments');
    }
};