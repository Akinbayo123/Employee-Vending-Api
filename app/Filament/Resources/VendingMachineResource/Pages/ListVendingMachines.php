<?php

namespace App\Filament\Resources\VendingMachineResource\Pages;

use App\Filament\Resources\VendingMachineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendingMachines extends ListRecords
{
    protected static string $resource = VendingMachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
