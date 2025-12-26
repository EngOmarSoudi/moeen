<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use Filament\Widgets\Widget;

class DriverLocationMapWidget extends Widget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.driver-location-map-widget';

    public function getDriverLocations(): array
    {
        return Driver::with('lastLocation')
            ->whereHas('lastLocation')
            ->whereIn('status', ['available', 'busy', 'on_break'])
            ->get()
            ->map(function ($driver) {
                $location = $driver->lastLocation;
                
                return [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'status' => $driver->status,
                    'latitude' => (float) $location->latitude,
                    'longitude' => (float) $location->longitude,
                    'speed' => $location->speed,
                    'heading' => $location->heading,
                    'address' => $location->address,
                    'recorded_at' => $location->recorded_at->diffForHumans(),
                ];
            })
            ->toArray();
    }

    protected function getViewData(): array
    {
        return [
            'drivers' => $this->getDriverLocations(),
            'title' => __('resources.dashboard.widgets.driver_location'),
        ];
    }
}
