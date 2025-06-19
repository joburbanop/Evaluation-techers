<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('description')->nullable()->after('name');
        });
    }
    
    public function down()
    {
        Schema::table('roles', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('description');
        });
    }
    

    
};
