<?php

namespace App\Filament\Resources\TaskIconResource\Pages;

use App\Filament\Resources\TaskIconResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskIcons extends ListRecords
{
    protected static string $resource = TaskIconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            // ->hidden(function(){
            //     return $this->getTableRecords()->count() > 0;
            // }),
        ];
    }
}
