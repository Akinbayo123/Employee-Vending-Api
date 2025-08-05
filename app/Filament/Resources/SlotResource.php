<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Slot;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SlotResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SlotResource\RelationManagers;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vending_machine_id')
                    ->label('Vending Machine')
                    ->relationship('vendingMachine', 'location')
                    ->required(),

                Forms\Components\TextInput::make('slot_number')
                    ->label('Slot Number')
                    ->numeric()
                    ->required()
                    ->rules([
                        fn(Get $get) => function ($attribute, $value, $fail) use ($get) {
                            $machineId = $get('vending_machine_id');
                            $recordId = request()->route('record')?->id;

                            $exists = Slot::where('slot_number', $value)
                                ->where('vending_machine_id', $machineId)
                                ->when($recordId, fn($q) => $q->where('id', '!=', $recordId))
                                ->exists();

                            if ($exists) {
                                $fail("Slot number {$value} is already used in this vending machine.");
                            }
                        },
                    ]),
                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options([
                        'juice' => 'Juice',
                        'meal' => 'Meal',
                        'snack' => 'Snack',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Points'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendingMachine.location')
                    ->label('Machine Location')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slot_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('price')

                    ->sortable(),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSlots::route('/'),
            'create' => Pages\CreateSlot::route('/create'),
            'view' => Pages\ViewSlot::route('/{record}'),
            'edit' => Pages\EditSlot::route('/{record}/edit'),
        ];
    }
}
