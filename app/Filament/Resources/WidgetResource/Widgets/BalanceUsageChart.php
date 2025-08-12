<?php

namespace App\Filament\Resources\WidgetResource\Widgets;

use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;


class BalanceUsageChart extends ChartWidget
{
    protected static ?string $heading = 'Balance Usage';


    public ?string $filter = 'last7'; // default filter

    protected function getFilters(): ?array
    {
        return [
            'last7' => 'Last 7 Days',
            'this_month' => 'This Month',

        ];
    }

    protected function getFormSchema(): array
    {
        if ($this->filter !== 'custom') {
            return [];
        }

        return [
            DatePicker::make('start_date')->label('Start Date')->required(),
            DatePicker::make('end_date')->label('End Date')->required(),
        ];
    }

    protected function getData(): array
    {
        $labels = [];
        $pointsAdded = [];
        $pointsDeducted = [];

        // Determine date range
        if ($this->filter === 'last7') {
            $start = Carbon::today()->subDays(6);
            $end = Carbon::today();
        } elseif ($this->filter === 'this_month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } else {
            $start = request()->get('start_date') ? Carbon::parse(request()->get('start_date')) : Carbon::today()->subDays(6);
            $end = request()->get('end_date') ? Carbon::parse(request()->get('end_date')) : Carbon::today();
        }

        // Loop through date range
        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $labels[] = $date->format('M d');

            // Points Added — only employees created on/before this date
            $added = Employee::whereDate('created_at', '<=', $date)
                ->with('classification')
                ->get()
                ->sum(fn($emp) => $emp->classification->daily_point_limit ?? 0);

            $pointsAdded[] = $added;

            // Points Deducted
            $deducted = Transaction::whereDate('created_at', $date)->sum('points_deducted') ?? 0;
            $pointsDeducted[] = $deducted;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Points Added',
                    'data' => $pointsAdded,
                    'borderColor' => '#10b981',
                    'backgroundColor' => '#10b981',
                    'fill' => false,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Points Deducted',
                    'data' => $pointsDeducted,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => '#ef4444',
                    'fill' => false,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
