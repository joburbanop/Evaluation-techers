<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar vistas si existen
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_area');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_institucion');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_facultad');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_programa');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_profesor');
        DB::statement('DROP VIEW IF EXISTS vw_niveles_competencia');

        // Vista para estadísticas de evaluaciones por área
        DB::statement("
            CREATE VIEW vw_evaluaciones_por_area AS
            SELECT 
                ta.id as test_assignment_id,
                ta.user_id,
                ta.test_id,
                ta.status,
                ta.created_at,
                u.name as user_name,
                u.apellido1,
                u.apellido2,
                u.email,
                u.institution_id,
                u.facultad_id,
                u.programa_id,
                i.name as institution_name,
                f.nombre as facultad_nombre,
                p.nombre as programa_nombre,
                q.area_id,
                a.name as area_name,
                a.description as area_description,
                tr.option_id,
                tr.score,
                tr.is_correct,
                opt.score as option_score,
                opt.feedback as option_feedback
            FROM test_assignments ta
            JOIN users u ON ta.user_id = u.id
            LEFT JOIN institutions i ON u.institution_id = i.id
            LEFT JOIN facultades f ON u.facultad_id = f.id
            LEFT JOIN programas p ON u.programa_id = p.id
            JOIN test_responses tr ON ta.id = tr.test_assignment_id
            JOIN questions q ON tr.question_id = q.id
            JOIN areas a ON q.area_id = a.id
            JOIN options opt ON tr.option_id = opt.id
            WHERE ta.status = 'completed'
            AND q.area_id != 8 -- Excluir área sociodemográfica
        ");

        // Vista para estadísticas de evaluaciones por institución
        DB::statement("
            CREATE VIEW vw_evaluaciones_por_institucion AS
            SELECT 
                i.id as institution_id,
                i.name as institution_name,
                i.academic_character,
                i.departamento_domicilio,
                i.municipio_domicilio,
                COUNT(DISTINCT ta.id) as total_evaluaciones,
                COUNT(DISTINCT ta.user_id) as total_usuarios,
                COUNT(DISTINCT u.facultad_id) as total_facultades,
                COUNT(DISTINCT u.programa_id) as total_programas,
                AVG(tr.score) as promedio_score,
                MAX(tr.score) as max_score,
                MIN(tr.score) as min_score,
                MAX(ta.created_at) as created_at
            FROM institutions i
            LEFT JOIN users u ON i.id = u.institution_id
            LEFT JOIN test_assignments ta ON u.id = ta.user_id AND ta.status = 'completed'
            LEFT JOIN test_responses tr ON ta.id = tr.test_assignment_id
            LEFT JOIN questions q ON tr.question_id = q.id AND q.area_id != 8
            GROUP BY i.id, i.name, i.academic_character, i.departamento_domicilio, i.municipio_domicilio
        ");

        // Vista para estadísticas de evaluaciones por facultad
        DB::statement("
            CREATE VIEW vw_evaluaciones_por_facultad AS
            SELECT 
                f.id as facultad_id,
                f.nombre as facultad_nombre,
                f.institution_id,
                i.name as institution_name,
                COUNT(DISTINCT ta.id) as total_evaluaciones,
                COUNT(DISTINCT ta.user_id) as total_usuarios,
                COUNT(DISTINCT u.programa_id) as total_programas,
                AVG(tr.score) as promedio_score,
                MAX(tr.score) as max_score,
                MIN(tr.score) as min_score,
                q.area_id,
                a.name as area_name,
                MAX(ta.created_at) as created_at
            FROM facultades f
            JOIN institutions i ON f.institution_id = i.id
            LEFT JOIN users u ON f.id = u.facultad_id
            LEFT JOIN test_assignments ta ON u.id = ta.user_id AND ta.status = 'completed'
            LEFT JOIN test_responses tr ON ta.id = tr.test_assignment_id
            LEFT JOIN questions q ON tr.question_id = q.id AND q.area_id != 8
            LEFT JOIN areas a ON q.area_id = a.id
            GROUP BY f.id, f.nombre, f.institution_id, i.name, q.area_id, a.name
        ");

        // Vista para estadísticas de evaluaciones por programa
        DB::statement("
            CREATE VIEW vw_evaluaciones_por_programa AS
            SELECT 
                p.id as programa_id,
                p.nombre as programa_nombre,
                p.facultad_id,
                f.nombre as facultad_nombre,
                f.institution_id,
                i.name as institution_name,
                COUNT(DISTINCT ta.id) as total_evaluaciones,
                COUNT(DISTINCT ta.user_id) as total_usuarios,
                AVG(tr.score) as promedio_score,
                MAX(tr.score) as max_score,
                MIN(tr.score) as min_score,
                q.area_id,
                a.name as area_name,
                MAX(ta.created_at) as created_at
            FROM programas p
            JOIN facultades f ON p.facultad_id = f.id
            JOIN institutions i ON f.institution_id = i.id
            LEFT JOIN users u ON p.id = u.programa_id
            LEFT JOIN test_assignments ta ON u.id = ta.user_id AND ta.status = 'completed'
            LEFT JOIN test_responses tr ON ta.id = tr.test_assignment_id
            LEFT JOIN questions q ON tr.question_id = q.id AND q.area_id != 8
            LEFT JOIN areas a ON q.area_id = a.id
            GROUP BY p.id, p.nombre, p.facultad_id, f.nombre, f.institution_id, i.name, q.area_id, a.name
        ");

        // Vista para estadísticas de evaluaciones por profesor
        DB::statement("
            CREATE VIEW vw_evaluaciones_por_profesor AS
            SELECT 
                u.id as user_id,
                u.name as user_name,
                u.apellido1,
                u.apellido2,
                u.email,
                u.institution_id,
                u.facultad_id,
                u.programa_id,
                i.name as institution_name,
                f.nombre as facultad_nombre,
                p.nombre as programa_nombre,
                COUNT(DISTINCT ta.id) as total_evaluaciones,
                AVG(tr.score) as promedio_score,
                MAX(tr.score) as max_score,
                MIN(tr.score) as min_score,
                SUM(tr.score) as total_score,
                COUNT(tr.id) as total_respuestas,
                q.area_id,
                a.name as area_name,
                MAX(ta.created_at) as created_at
            FROM users u
            LEFT JOIN institutions i ON u.institution_id = i.id
            LEFT JOIN facultades f ON u.facultad_id = f.id
            LEFT JOIN programas p ON u.programa_id = p.id
            LEFT JOIN test_assignments ta ON u.id = ta.user_id AND ta.status = 'completed'
            LEFT JOIN test_responses tr ON ta.id = tr.test_assignment_id
            LEFT JOIN questions q ON tr.question_id = q.id AND q.area_id != 8
            LEFT JOIN areas a ON q.area_id = a.id
            GROUP BY u.id, u.name, u.apellido1, u.apellido2, u.email, u.institution_id, u.facultad_id, u.programa_id, i.name, f.nombre, p.nombre, q.area_id, a.name
        ");

        // Vista para niveles de competencia (simplificada)
        DB::statement("
            CREATE VIEW vw_niveles_competencia AS
            SELECT 
                ta.id as test_assignment_id,
                ta.user_id,
                ta.test_id,
                u.name as user_name,
                u.facultad_id,
                u.programa_id,
                f.nombre as facultad_nombre,
                p.nombre as programa_nombre,
                q.area_id,
                a.name as area_name,
                SUM(tr.score) as total_score,
                COUNT(tr.id) as total_respuestas,
                AVG(tr.score) as promedio_score,
                ta.created_at
            FROM test_assignments ta
            JOIN users u ON ta.user_id = u.id
            LEFT JOIN facultades f ON u.facultad_id = f.id
            LEFT JOIN programas p ON u.programa_id = p.id
            JOIN test_responses tr ON ta.id = tr.test_assignment_id
            JOIN questions q ON tr.question_id = q.id AND q.area_id != 8
            JOIN areas a ON q.area_id = a.id
            WHERE ta.status = 'completed'
            GROUP BY ta.id, ta.user_id, ta.test_id, u.name, u.facultad_id, u.programa_id, f.nombre, p.nombre, q.area_id, a.name
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_area');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_institucion');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_facultad');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_programa');
        DB::statement('DROP VIEW IF EXISTS vw_evaluaciones_por_profesor');
        DB::statement('DROP VIEW IF EXISTS vw_niveles_competencia');
    }
}; 