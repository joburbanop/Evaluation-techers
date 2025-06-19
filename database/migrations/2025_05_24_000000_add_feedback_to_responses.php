<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_responses', function (Blueprint $table) {
            $table->text('feedback')->nullable()->after('score');
            $table->text('justification')->nullable()->after('feedback');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->text('feedback')->nullable()->after('score');
            $table->text('justification')->nullable()->after('feedback');
        });
    }

    public function down(): void
    {
        Schema::table('test_responses', function (Blueprint $table) {
            $table->dropColumn(['feedback', 'justification']);
        });

        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn(['feedback', 'justification']);
        });
    }
}; 