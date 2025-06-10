<?php

namespace App\Filament\Docente\Widgets;

use Filament\Widgets\ChartWidget;

class PersonalityRadarChart extends ChartWidget
{
    protected static ?string $heading = 'Estadisticas de evaluación';
    protected static ?string $maxHeight = '400px';

    // Almacena el máximo para la escala
    protected $maxScore = 0;

    protected function getData(): array
    {
        $user = auth()->user();
        $areas = \App\Models\Area::all();
        $labels = $areas->pluck('name')->toArray();
        $data = [];

        $max = 0;
        foreach ($areas as $area) {
            $promedio = \App\Models\TestResponse::where('user_id', $user->id)
                ->whereHas('question', function ($q) use ($area) {
                    $q->where('area_id', $area->id);
                })
                ->avg('score');

            $value = $promedio ? round($promedio, 2) : 0;
            $data[] = $value;
            $max = $value > $max ? $value : $max;
        }

        // Guarda el máximo para la escala (redondea hacia arriba a múltiplos de 10)
        $this->maxScore = $max > 0 ? ceil($max / 10) * 10 : 10;

        return [
            'datasets' => [
                [
                    'label' => 'Tus datos',
                    'data' => $data,
                    'backgroundColor' => 'rgba(0, 98, 255, 0.25)',
                    'borderColor' => '#3B82F6',
                    'pointBackgroundColor' => '#3B82F6',
                    'pointBorderColor' => '#1D4ED8',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }

    
    public function getDescription(): ?string
    {
        return 'Radar de fortalezas según tus evaluaciones.';
    }
}