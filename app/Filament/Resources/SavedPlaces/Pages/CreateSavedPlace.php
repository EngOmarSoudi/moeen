<?php

namespace App\Filament\Resources\SavedPlaces\Pages;

use App\Filament\Resources\SavedPlaces\SavedPlaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSavedPlace extends CreateRecord
{
    protected static string $resource = SavedPlaceResource::class;
}
