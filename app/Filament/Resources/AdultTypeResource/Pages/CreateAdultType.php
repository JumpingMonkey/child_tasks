<?php

namespace App\Filament\Resources\AdultTypeResource\Pages;

use App\Filament\Resources\AdultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdultType extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = AdultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // ...
        ];
    }
}
