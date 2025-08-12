<?php

namespace App\Filament\Resources\WidgetResource\Widgets;

use App\Models\Transaction;
use App\Models\VendingMachine;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalVendingMachines extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Machines', VendingMachine::count())
                ->description('All Vending Machines')
                ->descriptionIcon('heroicon-o-device-phone-mobile')
                ->color('primary'),
            Stat::make('Active Machines', VendingMachine::where('status', 'active')->count())
                ->description('All Active Vending Machines')
                ->descriptionIcon('heroicon-o-device-phone-mobile')
                ->color('success'),
            Stat::make('Inactive Machines', VendingMachine::where('status', 'inactive')->count())
                ->description('All Inactive Vending Machines')
                ->descriptionIcon('heroicon-o-device-phone-mobile')
                ->color('gray'),
        ];
    }
}
