<?php

namespace App\Filament\Resources\RealizarTestResource\Pages;

use App\Filament\Resources\RealizarTestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRealizarTest extends EditRecord
{
    protected static string $resource = RealizarTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
