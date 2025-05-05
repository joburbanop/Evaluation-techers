<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Inicio';

    protected static ?string $title = 'Panel de Administración';

    protected static ?int $navigationSort = 1;
} 