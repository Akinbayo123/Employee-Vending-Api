<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('vending_machine_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('slot_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('points_deducted')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('transaction_time')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('failure_reason')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendingMachine.location')
                    ->label('Machine Location')

                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.category')
                    ->label('Category')

                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.slot_number')
                    ->label('Slot Number')

                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points_deducted')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(string $state) => $state === 'success' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn(string $state) => $state === 'success' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('failure_reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
