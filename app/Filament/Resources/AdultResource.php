<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdultResource\Pages;
use App\Filament\Resources\AdultResource\RelationManagers;
use App\Models\Adult;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class AdultResource extends Resource
{
    protected static ?string $model = Adult::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('password')
                ->confirmed()
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create')
                ,
                Forms\Components\TextInput::make('password_confirmation')
                ->password(),
                Forms\Components\Select::make('tag_id')
                    ->label('Tag')
                    ->multiple()
                    ->searchable()
                    ->relationship('tags', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name'),
                Tables\Columns\TextColumn::make('registration date')
                    ->sortable()
                    ->getStateUsing(function(Model $model){
                        return $model->created_at;
                    }),
                Tables\Columns\TextColumn::make('children_count')->counts('children'),
                Tables\Columns\TextColumn::make('Gender')
                    ->getStateUsing(function(Model $model){
                        return $model->children()->first()?->gender ? 'boy' : 'girl';
                    }),
                Tables\Columns\TextColumn::make('Kid Name')
                    ->getStateUsing(function(Model $model){
                        return $model->children()->first()?->name;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Age')
                    ->getStateUsing(function(Model $model){
                        return $model->children()->first()?->age;
                    }),
                Tables\Columns\TextColumn::make('Parent Status')
                ->getStateUsing(function(Model $record){
                    return $record->children()->first()?->pivot->adult_type ?: '-';
                }),

                
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
            'index' => Pages\ListAdults::route('/'),
            'create' => Pages\CreateAdult::route('/create'),
            'edit' => Pages\EditAdult::route('/{record}/edit'),
        ];
    }    
}
