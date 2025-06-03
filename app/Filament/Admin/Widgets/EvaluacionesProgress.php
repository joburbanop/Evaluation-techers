<?php

namespace App\Filament\Widgets;

use App\Models\TestAssignment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class EvaluacionesProgress extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $stats = [
            'total' => TestAssignment::count(),
            'completadas' => TestAssignment::where('status', 'completed')->count(),
            'en_progreso' => TestAssignment::where('status', 'in_progress')->count(),
            'pendientes' => TestAssignment::where('status', 'pending')->count(),
        ];

        $porcentajeCompletadas = $stats['total'] > 0 
            ? round(($stats['completadas'] / $stats['total']) * 100) 
            : 0;

        return [
            Stat::make('Total de Evaluaciones', $stats['total'])
                ->description('Total de evaluaciones asignadas')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('gray'),

            Stat::make('Evaluaciones Completadas', $stats['completadas'])
                ->description("{$porcentajeCompletadas}% del total")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('En Progreso', $stats['en_progreso'])
                ->description('Evaluaciones en curso')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pendientes', $stats['pendientes'])
                ->description('Evaluaciones por iniciar')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger'),
        ];
    }
} 