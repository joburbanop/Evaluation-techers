<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;

class TeachersDonutChart extends ChartWidget
{
    protected static ?string $heading = 'Docentes por antigüedad';
    protected static string $color = 'info';

    protected function getData(): array
    {
        $now = now();
        $nuevos = User::role('Docente')->where('created_at', '>=', $now->copy()->subYear())->count();
        $intermedios = User::role('Docente')->whereBetween('created_at', [$now->copy()->subYears(3), $now->copy()->subYear()])->count();
        $experimentados = User::role('Docente')->where('created_at', '<', $now->copy()->subYears(3))->count();

        return [
            'datasets' => [
                [
                    'label' => 'Docentes',
                    'data' => [$nuevos, $intermedios, $experimentados],
                    'backgroundColor' => ['#36A2EB', '#6366F1', '#8B5CF6'],
                ],
            ],
            'labels' => ['Nuevos (<1 año)', 'Intermedios (1-3 años)', 'Experimentados (>3 años)'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
