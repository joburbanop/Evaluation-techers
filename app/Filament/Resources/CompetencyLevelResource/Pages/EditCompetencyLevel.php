<?php

namespace App\Filament\Resources\CompetencyLevelResource\Pages;

use App\Filament\Resources\CompetencyLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetencyLevel extends EditRecord
{
    protected static string $resource = CompetencyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->iconButton()
                ->color('danger'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 