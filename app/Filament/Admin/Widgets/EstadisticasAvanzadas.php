<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Institution;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstadisticasAvanzadas extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Estadísticas de instituciones
        $institutionStats = $this->getInstitutionStats();

        return [
            // Instituciones
            Stat::make('Instituciones Activas', $institutionStats['active_institutions'])
                ->description("{$institutionStats['total_institutions']} totales")
                ->descriptionIcon('heroicon-m-building-office')
                ->color('blue'),
        ];
    }



    private function getInstitutionStats(): array
    {
        $totalInstitutions = \App\Models\Institution::count();
        $activeInstitutions = \App\Models\Institution::whereHas('usuarios', function($query) {
            $query->whereHas('testAssignments', function($q) {
                $q->where('status', 'completed');
            });
        })->count();

        return [
            'total_institutions' => $totalInstitutions,
            'active_institutions' => $activeInstitutions,
        ];
    }


} 