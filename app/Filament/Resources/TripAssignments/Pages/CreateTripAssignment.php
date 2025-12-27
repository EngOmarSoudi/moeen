<?php

namespace App\Filament\Resources\TripAssignments\Pages;

use App\Filament\Resources\TripAssignments\TripAssignmentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateTripAssignment extends CreateRecord
{
    protected static string $resource = TripAssignmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validate that driver_id is a valid positive integer
        if (empty($data['driver_id']) || $data['driver_id'] == 0) {
            throw new Halt('Please select a valid driver before saving.');
        }

        // Validate that trip_id is a valid positive integer
        if (empty($data['trip_id']) || $data['trip_id'] == 0) {
            throw new Halt('Please select a valid trip before saving.');
        }

        return $data;
    }
}
