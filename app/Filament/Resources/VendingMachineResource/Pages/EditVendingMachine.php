<?php

namespace App\Filament\Resources\VendingMachineResource\Pages;

use App\Filament\Resources\VendingMachineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendingMachine extends EditRecord
{
    protected static string $resource = VendingMachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
