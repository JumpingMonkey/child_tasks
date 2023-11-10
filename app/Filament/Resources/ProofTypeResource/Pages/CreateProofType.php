<?php

namespace App\Filament\Resources\ProofTypeResource\Pages;

use App\Filament\Resources\ProofTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProofType extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ProofTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // ...
        ];
    }
}
