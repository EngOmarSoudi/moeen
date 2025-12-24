<?php

namespace App\Filament\Resources\CustomerStatuses\Pages;

use App\Filament\Resources\CustomerStatuses\CustomerStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerStatus extends EditRecord
{
    protected static string $resource = CustomerStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
