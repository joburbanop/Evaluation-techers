<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestAssignmentResource\Pages;
use App\Filament\Resources\TestAssignmentResource\RelationManagers;
use App\Models\InstitutionTest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestAssignmentResource extends Resource
{
    protected static ?string $model =  InstitutionTest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Test';
    protected static ?string $navigationLabel = 'Aginacion Intitucion';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\BelongsToSelect::make('institution_id')
                ->relationship('institution', 'name'), // Asumiendo que Institution tiene una columna 'name'
            Forms\Components\BelongsToSelect::make('test_id')
                ->relationship('test', 'name'), // Asumiendo que Test tiene una columna 'name'
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('institution.name'),
            Tables\Columns\TextColumn::make('test.name'),
        ])->filters([
            //
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestAssignments::route('/'),
            'create' => Pages\CreateTestAssignment::route('/create'),
            'edit' => Pages\EditTestAssignment::route('/{record}/edit'),
        ];
    }
}
