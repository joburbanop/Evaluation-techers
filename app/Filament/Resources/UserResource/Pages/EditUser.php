<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si no se proporciona contraseña, eliminar del array para no actualizar
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        // Si no se proporciona fecha de nacimiento, mantener la existente
        if (empty($data['date_of_birth'])) {
            unset($data['date_of_birth']);
        }
        
        return $data;
    }
}
