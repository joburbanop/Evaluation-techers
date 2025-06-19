<?php

namespace App\Filament\Resources\TestAssignmentResource\Pages;

use App\Filament\Resources\TestAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestAssignments extends ListRecords
{
    protected static string $resource = TestAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
