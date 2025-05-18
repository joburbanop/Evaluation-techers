<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Test;
use App\Models\TestAssignment;
use App\Models\User;
use App\Models\Institution;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Tests Activos', Test::where('is_active', true)->count())
                ->description('Total de evaluaciones disponibles')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('success')
                ->chart([7, 3, 5, 8, 10, 4, 8]) // Gráfica pequeña
                ->extraAttributes([
                    'class' => 'hover:shadow-lg transition-shadow duration-300' // Efecto hover
                ]),

            Stat::make('Asignaciones Pendientes', TestAssignment::where('status', 'pending')->count())
                ->description('Tests por realizar')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([2, 5, 3, 6, 8, 4, 6])
                ->extraAttributes([
                    'class' => 'hover:shadow-lg transition-shadow duration-300'
                ]),

            Stat::make('Tests Completados', TestAssignment::where('status', 'completed')->count())
                ->description('Evaluaciones finalizadas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([1, 3, 5, 7, 9, 11, 13])
                ->extraAttributes([
                    'class' => 'hover:shadow-lg transition-shadow duration-300 animate-pulse' // Animación sutil
                ]),

            Stat::make('Docentes Activos', User::role('teacher')->where('is_active', true)->count())
                ->description('Profesores en el sistema')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->chart([4, 6, 3, 7, 9, 5, 8])
                ->extraAttributes([
                    'class' => 'hover:shadow-lg transition-shadow duration-300'
                ]),

            Stat::make('Instituciones', Institution::count())
                ->description('Centros educativos registrados')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info')
                ->chart([1, 2, 3, 4, 5, 6, 7])
                ->extraAttributes([
                    'class' => 'hover:shadow-lg transition-shadow duration-300'
                ]),

            Stat::make('Tasa de Completitud', $this->getCompletionRate())
                ->description('Porcentaje de tests completados')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success')
                ->chart([10, 20, 30, 40, 50, 60, 70])
                ->extraAttributes([
                    'class' => 'hover:shadow-lg transition-shadow duration-300 bg-gradient-to-r from-green-50 to-white' // Fondo degradado
                ]),
        ];
    }

    protected function getCompletionRate(): string
    {
        $total = TestAssignment::count();
        if ($total === 0) return '0%';
        
        $completed = TestAssignment::where('status', 'completed')->count();
        $rate = ($completed / $total) * 100;
        
        return number_format($rate, 1) . '%';
    }
}