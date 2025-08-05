<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendingMachineResource\Pages;
use App\Filament\Resources\VendingMachineResource\RelationManagers;
use App\Models\VendingMachine;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendingMachineResource extends Resource
{
    protected static ?string $model = VendingMachine::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location')
                    ->label('Machine Location')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(string $state) => $state === 'active' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn(string $state) => $state === 'active' ? 'success' : 'danger')
                    ->action(fn($record) => $record->update([
                        'status' => $record->status === 'active' ? 'inactive' : 'active',
                    ]))
                    ->tooltip(fn(string $state) => $state === 'active' ? 'Click to deactivate' : 'Click to activate'),
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
            'index' => Pages\ListVendingMachines::route('/'),
            'create' => Pages\CreateVendingMachine::route('/create'),
            'view' => Pages\ViewVendingMachine::route('/{record}'),
            'edit' => Pages\EditVendingMachine::route('/{record}/edit'),
        ];
    }
}
