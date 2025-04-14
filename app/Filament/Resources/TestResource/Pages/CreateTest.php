<?php

namespace App\Filament\Resources\TestResource\Pages;

use App\Filament\Resources\TestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTest extends CreateRecord
{
    protected static string $resource = TestResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }
    protected function getFormActions(): array
    {
        return []; // Esto elimina los botones inferiores
    }
    protected function getHeaderActions(): array
    {
        return []; // Esto elimina acciones del header si las hay
    }

}
