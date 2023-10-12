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
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('coins')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('expected_duration')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Checkbox::make('is_active')
                    ->required(),
                Forms\Components\Select::make('proof_type_id')
                    ->label('Proof type')
                    ->relationship('proofType', 'title')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Select::make('schedule_id')
                    ->label('Schedule')
                    ->relationship('schedule', 'id')
                    ->createOptionForm([
                        Forms\Components\Checkbox::make('monday')
                            ->required(),
                        Forms\Components\Checkbox::make('tuesday')
                            ->required(),
                        Forms\Components\Checkbox::make('wednesday')
                            ->required(),
                        Forms\Components\Checkbox::make('thursday')
                            ->required(),
                        Forms\Components\Checkbox::make('friday')
                            ->required(),
                        Forms\Components\Checkbox::make('saturday')
                            ->required(),
                        Forms\Components\Checkbox::make('sunday')
                            ->required(),
                    ]),
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
            //
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
