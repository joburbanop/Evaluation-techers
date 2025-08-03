<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Test;
use App\Models\TestAssignment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProgresoPorEvaluacion extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $tests = Test::all();
        $stats = [];

        foreach ($tests as $test) {
            // Total de docentes en el sistema
            $totalDocentes = User::whereHas('roles', function($query) {
                $query->where('name', 'Docente');
            })->count();

            // Asignaciones para esta evaluación
            $asignaciones = TestAssignment::where('test_id', $test->id);
            $totalAsignaciones = $asignaciones->count();
            $completadas = $asignaciones->where('status', 'completed')->count();
            $enProgreso = $asignaciones->where('status', 'in_progress')->count();
            $pendientes = $asignaciones->where('status', 'pending')->count();

            // Porcentaje de avance
            $porcentajeAvance = $totalDocentes > 0 
                ? round(($completadas / $totalDocentes) * 100) 
                : 0;

            // Color basado en el porcentaje de avance
            $color = match (true) {
                $porcentajeAvance >= 80 => 'success',
                $porcentajeAvance >= 50 => 'warning',
                $porcentajeAvance >= 20 => 'info',
                default => 'danger',
            };

            $stats[] = Stat::make($test->name, $completadas)
                ->description("{$porcentajeAvance}% de avance - {$totalAsignaciones} asignadas")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($color)
                ->chart([
                    $pendientes,
                    $enProgreso,
                    $completadas
                ]);
        }

        return $stats;
    }
} 