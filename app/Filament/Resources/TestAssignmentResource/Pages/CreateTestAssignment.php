<?php

namespace App\Filament\Resources\TestAssignmentResource\Pages;

use App\Filament\Resources\TestAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestAssignment extends CreateRecord
{
    protected static string $resource = TestAssignmentResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
}
