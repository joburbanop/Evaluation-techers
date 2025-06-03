<?php

namespace App\Filament\Resources\CompetencyLevelResource\Pages;

use App\Filament\Resources\CompetencyLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompetencyLevel extends CreateRecord
{
    protected static string $resource = CompetencyLevelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 