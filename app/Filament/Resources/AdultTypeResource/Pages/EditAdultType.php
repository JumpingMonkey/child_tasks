<?php

namespace App\Filament\Resources\AdultTypeResource\Pages;

use App\Filament\Resources\AdultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdultType extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = AdultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
