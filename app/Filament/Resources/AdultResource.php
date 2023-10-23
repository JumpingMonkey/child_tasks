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
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Get;
use Filament\Forms\Set;

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
                ->required(fn (string $context): bool => $context === 'create'),

                Forms\Components\TextInput::make('password_confirmation')
                ->password(),
                Forms\Components\Checkbox::make('is_premium')
                    ->columnSpan(1)
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if(!$state){
                            $set('until', null);
                        }; 
                    }),
                Forms\Components\DatePicker::make('until')
                    // ->hidden(fn (Get $get) => $get('is_premium') !== true)
                    ->columnSpan(2),

                Forms\Components\Select::make('adult_type_id')
                    ->relationship('adultType', 'title')
                    ->required(),

                Forms\Components\Select::make('tag_id')
                    ->label('Tag')
                    ->multiple()
                    ->searchable()
                    ->relationship(name: 'tags', titleAttribute: 'name')
                    ->optionsLimit(5)
                    ->preload()
                    ->columnSpan(2)
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('adult_type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_premium')->boolean(),
                Tables\Columns\TextColumn::make('premium until')
                    ->sortable()
                    ->getStateUsing(function(Model $model){
                        return $model->until;
                    }),
                Tables\Columns\TextColumn::make('tags.name')
                ->badge(),
                Tables\Columns\TextColumn::make('registration date')
                    ->sortable()
                    ->getStateUsing(function(Model $model){
                        return $model->created_at;
                    }),
                Tables\Columns\TextColumn::make('children_count')
                    ->counts('children')
                    ->sortable(),
                
                
            ])
            ->filters([
                Filter::make('is_premium')
                    ->query(fn (Builder $query): Builder => $query->where('is_premium', true))
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
                    ])->tooltip('Actions'),
                    
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ChildrenRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdults::route('/'),
            'create' => Pages\CreateAdult::route('/create'),
            'view' => Pages\ViewAdult::route('/{record}'),
            'edit' => Pages\EditAdult::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
    return $infolist
    
        ->schema([
            Infolists\Components\TextEntry::make('name'),
            Infolists\Components\TextEntry::make('email'),
            Infolists\Components\TextEntry::make('created_at'),
            Infolists\Components\IconEntry::make('is_premium')
                ->boolean(),
            Infolists\Components\TextEntry::make('until'),
            Infolists\Components\TextEntry::make('adultType.title'),
               
        ]);

        
    }
}
