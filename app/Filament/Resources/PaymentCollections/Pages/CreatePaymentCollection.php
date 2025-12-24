<?php

namespace App\Filament\Resources\PaymentCollections\Pages;

use App\Filament\Resources\PaymentCollections\PaymentCollectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentCollection extends CreateRecord
{
    protected static string $resource = PaymentCollectionResource::class;
}
