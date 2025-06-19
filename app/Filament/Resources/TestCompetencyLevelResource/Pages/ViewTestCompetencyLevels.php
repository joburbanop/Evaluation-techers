<?php

namespace App\Filament\Resources\TestCompetencyLevelResource\Pages;

use App\Filament\Resources\TestCompetencyLevelResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Components;
use Illuminate\Support\Collection;

class ViewTestCompetencyLevels extends ViewRecord
{
    protected static string $resource = TestCompetencyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return 'Detalles del Test';
    }

    public function getInfolists(): array
    {
        return [
            Infolists\Infolist::make()
                ->schema([
                    Components\Section::make('Detalles del Test')
                        ->schema([
                            Components\TextEntry::make('name')
                                ->label('Nombre del Test'),
                            Components\TextEntry::make('description')
                                ->label('Descripción'),
                        ]),

                    Components\Section::make('Niveles de Competencia Global')
                        ->columns(3)
                        ->schema(
                            $this->record->globalCompetencyLevels->map(function ($nivel) {
                                return Components\Group::make([
                                    Components\TextEntry::make("nivel_{$nivel->id}")
                                        ->label("{$nivel->name} ({$nivel->code})")
                                        ->default("{$nivel->min_score} - {$nivel->max_score}: {$nivel->description}"),
                                ]);
                            })->toArray()
                        ),

                    Components\Section::make('Niveles por Área')
                        ->schema(
                            $this->record->areaCompetencyLevels()
                                ->with('area')
                                ->get()
                                ->groupBy(fn ($nivel) => $nivel->area?->name ?? 'Sin área')
                                ->map(function (Collection $niveles, string $areaName) {
                                    return Components\Group::make([
                                        Components\TextEntry::make("area_{$areaName}")
                                            ->label($areaName)
                                            ->default(
                                                $niveles->map(function ($nivel) {
                                                    return "{$nivel->code} ({$nivel->min_score}-{$nivel->max_score}): {$nivel->description}";
                                                })->implode("\n")
                                            )
                                    ]);
                                })->values()->toArray()
                        ),
                ]),
        ];
    }
}
