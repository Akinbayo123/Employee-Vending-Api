<?php

namespace App\Filament\Resources\WidgetResource\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalEmployees extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', Employee::count())
                ->description('All registered employees')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Active Employees', Employee::where('status', 'active')->count())
                ->description('All Active Employees')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
            Stat::make('Inactive Employees', Employee::where('status', 'inactive')->count())
                ->description('All Inactive Employees')
                ->descriptionIcon('heroicon-o-users')
                ->color('gray'),
        ];
    }
}
