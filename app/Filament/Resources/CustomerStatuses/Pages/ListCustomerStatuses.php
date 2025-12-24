<?php

namespace App\Filament\Resources\CustomerStatuses\Pages;

use App\Filament\Resources\CustomerStatuses\CustomerStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerStatuses extends ListRecords
{
    protected static string $resource = CustomerStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
