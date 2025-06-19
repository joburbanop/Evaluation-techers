<?php

namespace App\Filament\Resources\AreaCompetencyLevelResource\Pages;

use App\Filament\Resources\AreaCompetencyLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAreaCompetencyLevel extends EditRecord
{
    protected static string $resource = AreaCompetencyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
