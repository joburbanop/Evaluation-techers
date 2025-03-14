<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Filament\Resources\TestResource\RelationManagers;
use App\Models\Test;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Collection;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Tests';

    protected static ?string $label = 'Test';

    protected static ?string $pluralLabel = 'Tests';

    protected static ?string $slug = 'tests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Repeater::make('questions')
                    ->schema([
                        Forms\Components\TextInput::make('question')->required(),
                        Forms\Components\Repeater::make('options')
                            ->schema([
                                Forms\Components\TextInput::make('option')->required(),
                                Forms\Components\Checkbox::make('is_correct'),
                            ])
                            ->afterStateHydrated(function ($state, callable $set) {
                                $state = collect($state)->map(function ($item) {
                                    return array_merge(['is_correct' => false], $item);
                                });
                                $set($state->toArray());
                            }),
                    ])
                    ->createItemButtonLabel('Add Question'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
