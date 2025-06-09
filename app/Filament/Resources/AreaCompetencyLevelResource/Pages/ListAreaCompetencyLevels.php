<?php

namespace App\Filament\Resources\AreaCompetencyLevelResource\Pages;

use App\Filament\Resources\AreaCompetencyLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAreaCompetencyLevels extends ListRecords
{
    protected static string $resource = AreaCompetencyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
