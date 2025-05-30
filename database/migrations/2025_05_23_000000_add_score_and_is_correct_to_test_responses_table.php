<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_responses', function (Blueprint $table) {
            $table->float('score')->default(0)->after('user_id');
            $table->boolean('is_correct')->default(false)->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('test_responses', function (Blueprint $table) {
            $table->dropColumn(['score', 'is_correct']);
        });
    }
}; 