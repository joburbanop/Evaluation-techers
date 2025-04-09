<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealizarTestResource\Pages;
use App\Models\TestAssignment;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Log;

class RealizarTestResource extends Resource
{
    protected static ?string $model = TestAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static ?string $navigationLabel = 'Responder Test';
    protected static ?string $navigationGroup = 'Test';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Schema vacío ya que no usaremos el formulario tradicional
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.name')
                    ->label('Test Asignado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Fecha de Asignación')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('responder')
                    ->label('Responder')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->hidden(fn (TestAssignment $record) => $record->status !== 'pending')
                    ->modalHeading(fn (TestAssignment $record) => 'Responder: ' . $record->test->name)
                    ->modalSubmitActionLabel('Guardar respuestas')
                    ->form(function (TestAssignment $record) {
                        $questions = $record->test->questions()->with('options')->get();
                        $formFields = [];

                        foreach ($questions as $question) {
                            $formFields[] = Radio::make("answers.{$question->id}")
                                ->label($question->question)
                                ->options($question->options->pluck('option', 'id'))
                                ->required()
                                ->inline()
                                ->columnSpanFull();
                        }

                        return $formFields;
                    })
                    ->action(function (TestAssignment $record, array $data) {
                        // Guardar cada respuesta
                        foreach ($data['answers'] as $questionId => $optionId) {
                            $record->responses()->create([
                                'question_id' => $questionId,
                                'option_id' => $optionId,
                                'user_id' => auth()->id(),
                            ]);
                        }

                        // Actualizar el estado del test
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Aquí puedes agregar acciones masivas si las necesitas
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relaciones si son necesarias
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealizarTests::route('/'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    return parent::getEloquentQuery()
        ->where('user_id', auth()->id()) // Solo mostrar los test asignados al usuario
        ->with(['test.questions.options']); // Cargar preguntas y opciones de respuesta
}
}