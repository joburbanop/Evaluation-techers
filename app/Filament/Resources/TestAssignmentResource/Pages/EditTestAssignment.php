<?php

namespace App\Filament\Resources\TestAssignmentResource\Pages;

use App\Filament\Resources\TestAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestAssignment extends EditRecord
{
    protected static string $resource = TestAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
}
