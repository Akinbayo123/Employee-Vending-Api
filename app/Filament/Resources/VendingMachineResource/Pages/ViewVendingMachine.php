<?php

namespace App\Filament\Resources\VendingMachineResource\Pages;

use App\Filament\Resources\VendingMachineResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVendingMachine extends ViewRecord
{
    protected static string $resource = VendingMachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
