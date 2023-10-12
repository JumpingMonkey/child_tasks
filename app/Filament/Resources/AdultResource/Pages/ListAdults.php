<?php

namespace App\Filament\Resources\AdultResource\Pages;

use App\Filament\Resources\AdultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdults extends ListRecords
{
    protected static string $resource = AdultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
