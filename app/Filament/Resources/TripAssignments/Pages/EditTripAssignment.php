<?php

namespace App\Filament\Resources\TripAssignments\Pages;

use App\Filament\Resources\TripAssignments\TripAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTripAssignment extends EditRecord
{
    protected static string $resource = TripAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
