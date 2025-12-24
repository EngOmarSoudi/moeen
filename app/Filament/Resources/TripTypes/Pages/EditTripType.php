<?php

namespace App\Filament\Resources\TripTypes\Pages;

use App\Filament\Resources\TripTypes\TripTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTripType extends EditRecord
{
    protected static string $resource = TripTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
