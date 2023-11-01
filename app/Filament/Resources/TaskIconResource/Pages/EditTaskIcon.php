<?php

namespace App\Filament\Resources\TaskIconResource\Pages;

use App\Filament\Resources\TaskIconResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskIcon extends EditRecord
{
    protected static string $resource = TaskIconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
