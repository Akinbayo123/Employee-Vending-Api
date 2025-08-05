<?php

namespace App\Filament\Resources\WidgetResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalTransactions extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Transactions', Transaction::count())->color('primary'),
            Stat::make('Successful', Transaction::where('status', 'success')->count())->color('success'),
            Stat::make('Failed', Transaction::where('status', 'failure')->count())->color('danger'),
        ];
    }
}
