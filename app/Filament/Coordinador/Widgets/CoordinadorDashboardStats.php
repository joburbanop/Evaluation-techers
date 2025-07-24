<?php

namespace App\Filament\Coordinador\Widgets;

use App\Models\TestAssignment;
use App\Models\User;
use App\Models\Programa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CoordinadorDashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        // Estadísticas básicas
        $totalDocentes = User::whereHas('roles', function($q) {
            $q->where('name', 'Docente');
        })->where('facultad_id', $user->facultad_id)->count();

        $evaluacionesCompletadas = TestAssignment::whereHas('user', function($q) use ($user) {
            $q->where('facultad_id', $user->facultad_id);
        })->where('status', 'completed')->count();

        $evaluacionesPendientes = TestAssignment::whereHas('user', function($q) use ($user) {
            $q->where('facultad_id', $user->facultad_id);
        })->where('status', 'pending')->count();

        $totalProgramas = Programa::where('facultad_id', $user->facultad_id)->count();

        return [
            Stat::make('Docentes', $totalDocentes)
                ->description('En mi facultad')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Evaluaciones Completadas', $evaluacionesCompletadas)
                ->description('En mi facultad')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Evaluaciones Pendientes', $evaluacionesPendientes)
                ->description('En mi facultad')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Programas', $totalProgramas)
                ->description('En mi facultad')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('info'),
        ];
    }
} 