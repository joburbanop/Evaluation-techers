<?php

namespace App\Filament\Resources\CompetencyLevelResource\Pages;

use App\Filament\Resources\CompetencyLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompetencyLevels extends ListRecords
{
    protected static string $resource = CompetencyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Nivel')
                ->icon('heroicon-o-plus'),
        ];
    }
} 