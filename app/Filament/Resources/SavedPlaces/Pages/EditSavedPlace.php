<?php

namespace App\Filament\Resources\SavedPlaces\Pages;

use App\Filament\Resources\SavedPlaces\SavedPlaceResource;
use Filament\Resources\Pages\EditRecord;

class EditSavedPlace extends EditRecord
{
    protected static string $resource = SavedPlaceResource::class;
}
