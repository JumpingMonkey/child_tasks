<?php

namespace App\Filament\Resources\DefaultTaskResource\Pages;

use App\Filament\Resources\DefaultTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDefaultTasks extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    
    protected static string $resource = DefaultTaskResource::class;

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create new task'),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
