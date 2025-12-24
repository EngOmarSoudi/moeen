<?php

namespace App\Filament\Resources\CustomerStatuses\Pages;

use App\Filament\Resources\CustomerStatuses\CustomerStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerStatus extends CreateRecord
{
    protected static string $resource = CustomerStatusResource::class;
}
