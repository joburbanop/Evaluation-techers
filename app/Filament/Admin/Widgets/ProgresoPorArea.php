<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\TestAssignment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProgresoPorArea extends ChartWidget
{
    protected static ?string $heading = 'Progreso por Área';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $areas = Area::withCount(['questions' => function ($query) {
            $query->whereHas('responses', function ($q) {
                $q->whereHas('assignment', function ($q) {
                    $q->where('status', 'completed');
                });
            });
        }])->get();

        return [
            'datasets' => [
                [
                    'label' => 'Preguntas Respondidas',
                    'data' => $areas->pluck('questions_count')->toArray(),
                    'backgroundColor' => '#10B981',
                ],
            ],
            'labels' => $areas->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
} 