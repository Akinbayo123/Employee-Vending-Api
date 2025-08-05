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
            Stat::make('Total Machines', VendingMachine::count())->color('primary'),
            Stat::make('Active Machines', VendingMachine::where('status', 'active')->count())->color('success'),
            Stat::make('Inactive Machines', VendingMachine::where('status', 'inactive')->count())->color('gray'),
        ];
    }
}
