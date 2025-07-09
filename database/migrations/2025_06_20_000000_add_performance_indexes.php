<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Índices para test_assignments
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['test_id', 'status']);
        });

        // Índices para test_responses
        Schema::table('test_responses', function (Blueprint $table) {
            $table->index(['test_assignment_id', 'question_id']);
            $table->index(['question_id', 'option_id']);
            $table->index(['user_id', 'test_assignment_id']);
        });

        // Índices para questions
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['area_id', 'test_id']);
            $table->index(['test_id', 'area_id']);
        });

        // Índices para users
        Schema::table('users', function (Blueprint $table) {
            $table->index(['institution_id', 'facultad_id']);
            $table->index(['facultad_id', 'programa_id']);
            $table->index(['programa_id', 'institution_id']);
        });

        // Índices para facultades
        Schema::table('facultades', function (Blueprint $table) {
            $table->index(['institution_id', 'nombre']);
        });

        // Índices para programas
        Schema::table('programas', function (Blueprint $table) {
            $table->index(['facultad_id', 'nombre']);
        });

        // Índices para options
        Schema::table('options', function (Blueprint $table) {
            $table->index(['question_id', 'score']);
        });
    }

    public function down(): void
    {
        // Remover índices de test_assignments
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['test_id', 'status']);
        });

        // Remover índices de test_responses
        Schema::table('test_responses', function (Blueprint $table) {
            $table->dropIndex(['test_assignment_id', 'question_id']);
            $table->dropIndex(['question_id', 'option_id']);
            $table->dropIndex(['user_id', 'test_assignment_id']);
        });

        // Remover índices de questions
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['area_id', 'test_id']);
            $table->dropIndex(['test_id', 'area_id']);
        });

        // Remover índices de users
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['institution_id', 'facultad_id']);
            $table->dropIndex(['facultad_id', 'programa_id']);
            $table->dropIndex(['programa_id', 'institution_id']);
        });

        // Remover índices de facultades
        Schema::table('facultades', function (Blueprint $table) {
            $table->dropIndex(['institution_id', 'nombre']);
        });

        // Remover índices de programas
        Schema::table('programas', function (Blueprint $table) {
            $table->dropIndex(['facultad_id', 'nombre']);
        });

        // Remover índices de options
        Schema::table('options', function (Blueprint $table) {
            $table->dropIndex(['question_id', 'score']);
        });
    }
}; 