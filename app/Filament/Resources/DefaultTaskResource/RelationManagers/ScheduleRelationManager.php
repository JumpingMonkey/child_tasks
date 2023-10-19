<?php

namespace App\Filament\Resources\DefaultTaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleRelationManager extends RelationManager
{
    protected static string $relationship = 'schedule';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Checkbox::make('monday'),
                Forms\Components\Checkbox::make('tuesday'),
                Forms\Components\Checkbox::make('wednesday'),
                Forms\Components\Checkbox::make('thursday'),
                Forms\Components\Checkbox::make('friday'),
                Forms\Components\Checkbox::make('saturday'),
                Forms\Components\Checkbox::make('sunday'),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('monday')
                    ->getStateUsing(fn(Model $model) => $model->monday ? 'active' : '-'),
                Tables\Columns\TextColumn::make('tuesday')
                ->getStateUsing(fn(Model $model) => $model->tuesday ? 'active' : '-'),
                Tables\Columns\TextColumn::make('wednesday')
                ->getStateUsing(fn(Model $model) => $model->wednesday ? 'active' : '-'),
                Tables\Columns\TextColumn::make('thursday')
                ->getStateUsing(fn(Model $model) => $model->thursday ? 'active' : '-'),
                Tables\Columns\TextColumn::make('friday')
                ->getStateUsing(fn(Model $model) => $model->friday ? 'active' : '-'),
                Tables\Columns\TextColumn::make('saturday')
                ->getStateUsing(fn(Model $model) => $model->saturday ? 'active' : '-'),
                Tables\Columns\TextColumn::make('sunday')
                ->getStateUsing(fn(Model $model) => $model->sunday ? 'active' : '-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
