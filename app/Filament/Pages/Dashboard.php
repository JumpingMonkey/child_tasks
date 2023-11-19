<?php

namespace App\Filament\Pages;
 
class Dashboard extends \Filament\Pages\Dashboard 
{
    // protected static ?string $navigationIcon = 'ri-dashboard-fill';

    // protected static ?string $navigationLabel = 'ddd';

    public function getColumns(): int | string | array
    {
        return 4;
    }
}
