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
        // ✅ ÍNDICES CRÍTICOS PARA OPTIMIZAR REPORTES
        
        // Índice para test_assignments (consultas por user_id y test_id)
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->index(['user_id', 'test_id'], 'idx_test_assignments_user_test');
            $table->index(['status'], 'idx_test_assignments_status');
        });
        
        // Índice para test_responses (consultas por test_assignment_id)
        Schema::table('test_responses', function (Blueprint $table) {
            $table->index(['test_assignment_id'], 'idx_test_responses_assignment');
            $table->index(['question_id'], 'idx_test_responses_question');
        });
        
        // Índice para users (consultas por institution_id y facultad_id)
        Schema::table('users', function (Blueprint $table) {
            $table->index(['institution_id', 'facultad_id'], 'idx_users_institution_facultad');
            $table->index(['programa_id'], 'idx_users_programa');
        });
        
        // Índice para facultades (consultas por institution_id y nombre)
        Schema::table('facultades', function (Blueprint $table) {
            $table->index(['institution_id', 'nombre'], 'idx_facultades_institution_nombre');
        });
        
        // Índice para programas (consultas por facultad_id)
        Schema::table('programas', function (Blueprint $table) {
            $table->index(['facultad_id'], 'idx_programas_facultad');
        });
        
        // Índice para questions (consultas por test_id)
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['test_id'], 'idx_questions_test');
        });
        
        // Índice para options (consultas por question_id)
        Schema::table('options', function (Blueprint $table) {
            $table->index(['question_id'], 'idx_options_question');
        });
        
        // Índice para tests (consultas por is_active)
        Schema::table('tests', function (Blueprint $table) {
            $table->index(['is_active'], 'idx_tests_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover índices en orden inverso
        Schema::table('tests', function (Blueprint $table) {
            $table->dropIndex('idx_tests_active');
        });
        
        Schema::table('options', function (Blueprint $table) {
            $table->dropIndex('idx_options_question');
        });
        
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('idx_questions_test');
        });
        
        Schema::table('programas', function (Blueprint $table) {
            $table->dropIndex('idx_programas_facultad');
        });
        
        Schema::table('facultades', function (Blueprint $table) {
            $table->dropIndex('idx_facultades_institution_nombre');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_institution_facultad');
            $table->dropIndex('idx_users_programa');
        });
        
        Schema::table('test_responses', function (Blueprint $table) {
            $table->dropIndex('idx_test_responses_assignment');
            $table->dropIndex('idx_test_responses_question');
        });
        
        Schema::table('test_assignments', function (Blueprint $table) {
            $table->dropIndex('idx_test_assignments_user_test');
            $table->dropIndex('idx_test_assignments_status');
        });
    }
};
