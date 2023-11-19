<?php

namespace App\Filament\Widgets;

use App\Models\Adult;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AcountsStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $adults = Adult::count();
        $freeAccounts = Adult::where('is_premium', false)->count();
        $premiumAccounts = Adult::where('is_premium', true)->count();
        $newTodayAccounts = Adult::whereDate('created_at', Carbon::today())->count();
        $freeTodayAccounts = Adult::where('is_premium', false)->whereDate('created_at', Carbon::today())->count();
        $premiumTodayAccounts = Adult::where('is_premium', true)->whereDate('created_at', Carbon::today())->count();
        return [
            Stat::make('Unique Accounts', $adults)
            ->icon('heroicon-o-users')
            ->description(" + $newTodayAccounts increase")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Stat::make('Free accounts', $freeAccounts)
            ->icon('heroicon-o-users')
            ->description(" + $freeTodayAccounts increase")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Stat::make('Premium accounts', $premiumAccounts)
            ->icon('heroicon-o-users')
            ->description(" + $premiumTodayAccounts increase")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
        ];
    }
}
