<?php

namespace App\Filament\Forms\Components;

use App\Models\SavedPlace;
use Filament\Forms\Components\Field;

class TripLocationPicker extends Field
{
    protected string $view = 'filament.forms.components.trip-location-picker';

    protected string $locationType = 'origin';
    protected string $nameField = 'origin';
    protected string $latField = 'origin_lat';
    protected string $lngField = 'origin_lng';

    public static function make(?string $name = null): static
    {
        $static = parent::make($name);
        
        if ($name === 'destination') {
            $static->locationType('destination');
        } else {
            $static->locationType('origin');
        }

        return $static;
    }

    public function locationType(string $type): static
    {
        $this->locationType = $type;
        
        if ($type === 'destination') {
            $this->nameField = 'destination';
            $this->latField = 'destination_lat';
            $this->lngField = 'destination_lng';
        } else {
            $this->nameField = 'origin';
            $this->latField = 'origin_lat';
            $this->lngField = 'origin_lng';
        }
        
        return $this;
    }

    public function getLocationType(): string
    {
        return $this->locationType;
    }

    public function getNameField(): string
    {
        return $this->nameField;
    }

    public function getLatField(): string
    {
        return $this->latField;
    }

    public function getLngField(): string
    {
        return $this->lngField;
    }

    public function getSavedPlaces(): array
    {
        return SavedPlace::active()
            ->orderBy('name')
            ->get()
            ->map(fn ($place) => [
                'id' => $place->id,
                'name' => $place->name,
                'address' => $place->address,
                'lat' => (float) $place->latitude,
                'lng' => (float) $place->longitude,
                'type' => $place->place_type,
            ])
            ->toArray();
    }
}
