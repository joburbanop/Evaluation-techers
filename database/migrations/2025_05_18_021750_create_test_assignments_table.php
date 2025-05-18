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
            // Agregar columnas si no existen
            if (!Schema::hasColumn('test_assignments', 'due_at')) {
                $table->timestamp('due_at')->nullable();
            }
            if (!Schema::hasColumn('test_assignments', 'started_at')) {
                $table->timestamp('started_at')->nullable();
            }
            if (!Schema::hasColumn('test_assignments', 'score')) {
                $table->integer('score')->nullable();
            }
            if (!Schema::hasColumn('test_assignments', 'feedback')) {
                $table->text('feedback')->nullable();
            }
            if (!Schema::hasColumn('test_assignments', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            }

            // Modificar el enum status si existe
            if (Schema::hasColumn('test_assignments', 'status')) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'expired'])->default('pending')->change();
            } else {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'expired'])->default('pending');
            }

            // Agregar índices si no existen
            if (!Schema::hasIndex('test_assignments', ['user_id', 'status'])) {
                $table->index(['user_id', 'status']);
            }
            if (!Schema::hasIndex('test_assignments', ['due_at'])) {
                $table->index('due_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_assignments', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['due_at']);

            // Eliminar columnas
            $table->dropColumn([
                'due_at',
                'started_at',
                'score',
                'feedback',
                'assigned_by'
            ]);
        });
    }
};
