<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskIconResource\Pages;
use App\Filament\Resources\TaskIconResource\RelationManagers;
use App\Models\TaskIcon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskIconResource extends Resource
{
    protected static ?string $model = TaskIcon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Tasks';

    protected static ?string $label = 'Default task icon';

    protected static ?string $navigationLabel = 'Default task icon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('filename')
                ->disk('public')
                ->directory('default-task-icon')                
                ->image()
                ->imageEditor()->label('icon')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('filename')->label('icon'),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make()
                    // ->requiresConfirmation(),
                    
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
            'index' => Pages\ListTaskIcons::route('/'),
            'create' => Pages\CreateTaskIcon::route('/create'),
            'edit' => Pages\EditTaskIcon::route('/{record}/edit'),
            'view' => Pages\ViewTaskIcon::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('filename')
                ->label('icon')
                ->size(400)
            ]);
    }
}
