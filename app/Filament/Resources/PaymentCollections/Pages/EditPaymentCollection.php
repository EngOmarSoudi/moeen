<?php

namespace App\Filament\Resources\PaymentCollections\Pages;

use App\Filament\Resources\PaymentCollections\PaymentCollectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentCollection extends EditRecord
{
    protected static string $resource = PaymentCollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
