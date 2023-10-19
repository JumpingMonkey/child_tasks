<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DefaultTaskResource\Pages;
use App\Filament\Resources\DefaultTaskResource\RelationManagers;
use App\Models\DefaultTask;
use App\Models\GeneralAvailableRegularTaskTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DefaultTaskResource extends Resource
{
    protected static ?string $model = GeneralAvailableRegularTaskTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('coins')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('expected_duration')
                    ->maxLength(255)
                    ->hint('Number of seconds'),
                Forms\Components\Checkbox::make('is_active')
                    ->required()
                    ->default(true),
                Forms\Components\Select::make('proof_type_id')
                    ->label('Proof type')
                    ->relationship('proofType', 'title'),
                    // ->createOptionForm([
                    //     Forms\Components\TextInput::make('title')
                    //         ->required()
                    //         ->maxLength(255),
                    // ]),
                    Forms\Components\Fieldset::make('Schedule')
                        ->schema([
                            Forms\Components\Checkbox::make('monday'),
                            Forms\Components\Checkbox::make('tuesday'),
                            Forms\Components\Checkbox::make('wednesday'),
                            Forms\Components\Checkbox::make('thursday'),
                            Forms\Components\Checkbox::make('friday'),
                            Forms\Components\Checkbox::make('saturday'),
                            Forms\Components\Checkbox::make('sunday'),
                        ])
                        
                        ->columns(7),
                // Forms\Components\Select::make('schedule_id')
                //     ->label('Schedule')
                //     ->relationship(name: 'schedule')
                //     ->getOptionLabelFromRecordUsing(
                //         function (Model $record){
                //             $monday = $record->monday ? 'Mon': '';
                //             $tuesday = $record->tuesday ? 'Tues': '';
                //             $wednesday = $record->wednesday ? 'Wed': '';
                //             $thursday = $record->thursday ? 'Thurs': '';
                //             $friday = $record->friday ? 'Fri': '';
                //             $saturday = $record->saturday ? 'Sat': '';
                //             $sunday = $record->sunday ? 'Sun': '';
                //             return "{$monday} {$tuesday} {$wednesday} {$thursday} {$friday} {$saturday} {$sunday}";
                //         })                 
                    // ->createOptionForm([
                    //     Forms\Components\Checkbox::make('monday'),
                    //     Forms\Components\Checkbox::make('tuesday'),
                    //     Forms\Components\Checkbox::make('wednesday'),
                    //     Forms\Components\Checkbox::make('thursday'),
                    //     Forms\Components\Checkbox::make('friday'),
                    //     Forms\Components\Checkbox::make('saturday'),
                    //     Forms\Components\Checkbox::make('sunday'),
                    // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('coins')
                    ->searchable(),
                Tables\Columns\CheckboxColumn::make('is_active')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            // RelationManagers\ScheduleRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDefaultTasks::route('/'),
            'create' => Pages\CreateDefaultTask::route('/create'),
            'edit' => Pages\EditDefaultTask::route('/{record}/edit'),
        ];
    } 
}
