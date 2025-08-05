<?php

namespace App\Filament\Resources\TableResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactions extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->with(['employee', 'vendingMachine'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('vendingMachine.location')
                    ->label('Machine')
                    ->sortable(),

                Tables\Columns\TextColumn::make('points_deducted')
                    ->label('Points')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(string $state) => $state === 'failed' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn(string $state) => $state === 'sucess' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('transaction_time')
                    ->label('Time')
                    ->since()
                    ->sortable(),
            ]);
    }
}
