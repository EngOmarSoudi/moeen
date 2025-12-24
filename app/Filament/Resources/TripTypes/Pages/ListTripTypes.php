<?php

namespace App\Filament\Resources\TripTypes\Pages;

use App\Filament\Resources\TripTypes\TripTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTripTypes extends ListRecords
{
    protected static string $resource = TripTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
