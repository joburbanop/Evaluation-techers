<?php

namespace App\Filament\Docente\Pages;

use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Panel de Docente';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Inicio';
    }

    public static function getRouteName(?string $panel = null): string
    {
        return 'filament.docente.pages.dashboard';
    }
} 