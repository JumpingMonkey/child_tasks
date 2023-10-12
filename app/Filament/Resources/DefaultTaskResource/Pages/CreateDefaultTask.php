<?php

namespace App\Filament\Resources\DefaultTaskResource\Pages;

use App\Filament\Resources\DefaultTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDefaultTask extends CreateRecord
{
    protected static string $resource = DefaultTaskResource::class;
}
