<?php

namespace App\Filament\Resources\TravelRoutes\Pages;

use App\Filament\Resources\TravelRoutes\TravelRouteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTravelRoute extends CreateRecord
{
    protected static string $resource = TravelRouteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure origin and destination are not empty strings
        if (empty($data['origin'])) {
            $data['origin'] = null;
        }
        if (empty($data['destination'])) {
            $data['destination'] = null;
        }
        
        return $data;
    }
}
