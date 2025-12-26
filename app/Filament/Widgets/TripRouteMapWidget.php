<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use Filament\Widgets\Widget;

class TripRouteMapWidget extends Widget
{
    public ?Trip $record = null;

    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.trip-route-map-widget';

    public function getScheduledTrips(): array
    {
        $query = Trip::with(['customer', 'agent', 'routeTemplate']);

        if ($this->record) {
            // Single trip view - show this specific trip with all required data
            $query->where('id', $this->record->id);
        } else {
            // Dashboard view - show scheduled and in_progress trips
            $query->whereIn('status', ['scheduled', 'in_progress'])
                ->whereNotNull('origin_lat')
                ->whereNotNull('origin_lng')
                ->orderBy('start_at')
                ->limit(20);
        }

        return $query->with(['latestTrackingPoint'])->get()
            ->filter(function ($trip) {
                return $trip->origin_lat && $trip->origin_lng;
            })
            ->map(function ($trip) {
                $latestPoint = $trip->latestTrackingPoint;
                
                return [
                    'id' => $trip->id,
                    'code' => $trip->code,
                    'customer_name' => $trip->customer?->name ?? 'Unknown',
                    'origin_name' => $trip->origin ?? 'Pickup Location',
                    'origin_lat' => (float) $trip->origin_lat,
                    'origin_lng' => (float) $trip->origin_lng,
                    'destination_name' => $trip->destination ?? 'Drop-off Location',
                    'destination_lat' => (float) $trip->destination_lat,
                    'destination_lng' => (float) $trip->destination_lng,
                    'current_lat' => $latestPoint ? (float) $latestPoint->latitude : null,
                    'current_lng' => $latestPoint ? (float) $latestPoint->longitude : null,
                    'start_at' => $trip->start_at?->format('M d, Y H:i'),
                    'passenger_count' => $trip->passenger_count,
                    'final_amount' => number_format($trip->final_amount ?? 0, 2),
                    'status' => $trip->status ?? 'scheduled',
                    'service_kind' => $trip->service_kind ?? 'transfer',
                ];
            })
            ->values()
            ->toArray();
    }

    protected function getViewData(): array
    {
        return [
            'trips' => $this->getScheduledTrips(),
            'title' => __('resources.dashboard.widgets.trip_route_map'),
        ];
    }
}
