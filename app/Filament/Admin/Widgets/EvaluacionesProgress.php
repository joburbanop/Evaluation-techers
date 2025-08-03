<?php

namespace App\Filament\Admin\Widgets;

use App\Models\TestAssignment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class EvaluacionesProgress extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Total de usuarios/docentes en el sistema
        $totalUsuarios = User::whereHas('roles', function($query) {
            $query->where('name', 'Docente');
        })->count();

        // Usuarios que han completado ambas evaluaciones
        $usuariosCompletados = User::whereHas('roles', function($query) {
            $query->where('name', 'Docente');
        })->whereHas('testAssignments', function($query) {
            $query->where('status', 'completed');
        }, '>=', 2)->count();

        // Usuarios que han completado al menos una evaluación
        $usuariosConUnaEvaluacion = User::whereHas('roles', function($query) {
            $query->where('name', 'Docente');
        })->whereHas('testAssignments', function($query) {
            $query->where('status', 'completed');
        }, '>=', 1)->count();

        // Usuarios sin evaluaciones completadas
        $usuariosSinEvaluaciones = $totalUsuarios - $usuariosConUnaEvaluacion;

        $porcentajeCompletados = $totalUsuarios > 0 
            ? round(($usuariosCompletados / $totalUsuarios) * 100) 
            : 0;

        return [
            Stat::make('Total de Docentes', $totalUsuarios)
                ->description('Usuarios docentes en el sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('blue'),

            Stat::make('Docentes Completados', $usuariosCompletados)
                ->description("{$porcentajeCompletados}% han completado ambas evaluaciones")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Con Una Evaluación', $usuariosConUnaEvaluacion - $usuariosCompletados)
                ->description('Han completado al menos una evaluación')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Sin Evaluaciones', $usuariosSinEvaluaciones)
                ->description('No han completado ninguna evaluación')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger'),
        ];
    }
} 