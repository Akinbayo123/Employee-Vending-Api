<?php

namespace App\Filament\Resources\WidgetResource\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class MostConsumedProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Most Consumed Products';

    public ?string $filter = 'this_month'; // default filter

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'this_week' => 'This Week',
            'this_month' => 'This Month',
            'last_30' => 'Last 30 Days',
        ];
    }

    protected function getData(): array
    {
        // Apply date filter
        $query = Transaction::query();

        if ($this->filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->filter === 'this_week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->filter === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        } elseif ($this->filter === 'last_30') {
            $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]);
        }

        // Group by slot
        $products = $query->selectRaw('slot_id, COUNT(*) as total')
            ->groupBy('slot_id')
            ->with('slot')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Purchases',
                    'data' => $products->pluck('total')->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                ],
            ],
            'labels' => $products->pluck('slot.category')->toArray(),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
