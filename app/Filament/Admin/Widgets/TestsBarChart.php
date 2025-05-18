<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Test;

class TestsBarChart extends ChartWidget
{
    protected static ?string $heading = 'Tests creados por semana';
    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = Test::selectRaw('COUNT(*) as total, WEEK(created_at) as week')
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Tests',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#059669',
                ],
            ],
            'labels' => $data->map(fn ($item) => "Semana {$item->week}"),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
