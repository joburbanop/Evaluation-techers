<?php

namespace App\Filament\Resources\TestCompetencyLevelResource\Pages;

use App\Filament\Resources\TestCompetencyLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestCompetencyLevels extends ListRecords
{
    protected static string $resource = TestCompetencyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
