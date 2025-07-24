<?php

namespace App\Filament\Coordinador\Pages;

use Filament\Pages\Dashboard as BasePage;
use App\Filament\Coordinador\Widgets\CoordinadorDashboardStats;
use App\Filament\Coordinador\Widgets\InformacionTecnica;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Panel de Coordinación';

    protected static ?int $navigationSort = 1;



    
} 