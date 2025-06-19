<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_assignments', function (Blueprint $table) {
            $columns = [
                'assigned_at',
                'due_at',
                'completed_at',
                'assigned_date',
                'due_date',
                'assigned_time',
                'due_time',
                'duration_label'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('test_assignments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->date('assigned_date')->nullable();
            $table->date('due_date')->nullable();
            $table->time('assigned_time')->nullable();
            $table->time('due_time')->nullable();
            $table->string('duration_label')->nullable();
        });
    }
};
