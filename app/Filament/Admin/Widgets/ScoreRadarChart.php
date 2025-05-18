<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class ScoreRadarChart extends ChartWidget
{
    protected static ?string $heading = 'Radar de resultados (ejemplo)';
    protected static string $color = 'primary';

    protected function getData(): array
    {
       
        return [
            'datasets' => [
                [
                    'label' => 'Resultados promedio',
                    'data' => [70, 85, 65, 90, 75],
                    'backgroundColor' => 'rgba(59,130,246,0.4)',
                    'borderColor' => '#2563eb',
                ],
            ],
            'labels' => ['Comunicación', 'Innovación', 'Liderazgo', 'Trabajo en equipo', 'Gestión'],
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }
}
