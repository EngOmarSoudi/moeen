<?php

namespace App\Filament\Resources\SavedPlaces\Pages;

use App\Filament\Resources\SavedPlaces\SavedPlaceResource;
use Filament\Resources\Pages\ListRecords;

class ListSavedPlaces extends ListRecords
{
    protected static string $resource = SavedPlaceResource::class;
}
