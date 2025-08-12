<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\EmployeeResource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class EmployeeTransactions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.resources.employee-resource.pages.employee-transactions';

    public int $employeeId;

    public function mount($record): void
    {
        $this->employeeId = (int) $record;
    }

    protected function getTableQuery(): Builder
    {
        return Transaction::query()
            ->where('employee_id', $this->employeeId)
            ->with(['vendingMachine', 'slot'])
            ->orderByDesc('transaction_time');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('vendingMachine.location')->label('Machine'),
            Tables\Columns\TextColumn::make('slot.category')->label('Category'),
            Tables\Columns\TextColumn::make('points_deducted')->label('Points'),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn(string $state) => match ($state) {
                    'success' => 'success',
                    'failed' => 'danger',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('transaction_time')->since()->label('Time'),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'transaction_time';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
