<?php

namespace App\Filament\Resources\DefaultTaskResource\Pages;

use App\Filament\Resources\DefaultTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDefaultTask extends EditRecord
{
    protected static string $resource = DefaultTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
