<?php

namespace App\Filament\Resources\ProofTypeResource\Pages;

use App\Filament\Resources\ProofTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProofType extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = ProofTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
