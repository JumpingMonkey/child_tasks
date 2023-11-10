<?php

namespace App\Filament\Resources\AdultTypeResource\Pages;

use App\Filament\Resources\AdultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdultTypes extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = AdultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
