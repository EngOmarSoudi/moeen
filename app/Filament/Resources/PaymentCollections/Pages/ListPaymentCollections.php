<?php

namespace App\Filament\Resources\PaymentCollections\Pages;

use App\Filament\Resources\PaymentCollections\PaymentCollectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentCollections extends ListRecords
{
    protected static string $resource = PaymentCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
