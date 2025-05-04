<?php

namespace App\Filament\Pages\Docente;

use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Inicio';
    protected static ?string $title = 'Panel de Docente';
    protected static ?string $slug = 'dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            FilamentInfoWidget::class,
        ];
    }
} 