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
            Stat::make('Total Transactions', Transaction::count())
                ->description('All Transactions')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('primary'),
            Stat::make('Successful', Transaction::where('status', 'success')->count())
                ->description('All Successful Transactions')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('success'),
            Stat::make('Failed', Transaction::where('status', 'failure')->count())
                ->description('All Failed Transactions')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('danger'),
        ];
    }
}
