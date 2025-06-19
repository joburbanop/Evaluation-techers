<?php

namespace App\Filament\Resources\RealizarTestResource\Pages;

use App\Filament\Resources\RealizarTestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRealizarTests extends ListRecords
{
    protected static string $resource = RealizarTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
