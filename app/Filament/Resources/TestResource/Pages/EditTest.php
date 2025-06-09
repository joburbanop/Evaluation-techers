<?php

namespace App\Filament\Resources\TestResource\Pages;

use App\Filament\Resources\TestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTest extends EditRecord
{
    protected static string $resource = TestResource::class;

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

protected function afterSave(): void
{
    $test = $this->record;
    $data = $this->form->getRawState();

    foreach ($data['questions'] ?? [] as $question) {
        $areaId = $question['area_id'] ?? null;
        $niveles = $question['editable_test_area_levels'] ?? [];

        if ($areaId && !empty($niveles)) {
            foreach ($niveles as $nivel) {
                if (isset($nivel['id'])) {
                    \App\Models\TestAreaCompetencyLevel::where('id', $nivel['id'])->update([
                        'name' => $nivel['name'],
                        'code' => $nivel['code'],
                        'min_score' => $nivel['min_score'],
                        'max_score' => $nivel['max_score'],
                        'description' => $nivel['description'],
                    ]);
                } else {
                    \App\Models\TestAreaCompetencyLevel::create([
                        'test_id' => $test->id,
                        'area_id' => $areaId,
                        'name' => $nivel['name'],
                        'code' => $nivel['code'],
                        'min_score' => $nivel['min_score'],
                        'max_score' => $nivel['max_score'],
                        'description' => $nivel['description'],
                    ]);
                }
            }
        }
    }
}



   
    
}
