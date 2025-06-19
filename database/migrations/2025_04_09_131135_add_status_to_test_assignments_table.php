<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'completed', 'expired'])->default('pending');
        });
    }
    
    public function down()
    {
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
