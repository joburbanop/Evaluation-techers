<?php

namespace App\Filament\Docente\Widgets;

use Filament\Widgets\ChartWidget;

class PersonalityRadarChart extends ChartWidget
{
    protected static ?string $heading = 'Estadisticas de evaluación';

    protected static ?string $maxHeight = '400px'; 

    protected function getData(): array
    {
        
        $labels = [
            'Responsabilidad',
            'Empatía',
            'Liderazgo',
            'Creatividad',
            'Comunicación',
            'Disciplina',
        ];

        $data = [
            90,  
            65,  
            75,  
            85,  
            70,  
            95,  
        ];

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
