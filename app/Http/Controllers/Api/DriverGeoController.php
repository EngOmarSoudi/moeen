<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

/**
 * Returns a list of drivers with geographic coordinates for map display.
 * Supports:
 * - last_lat / last_lng fields
 * - last_gps as text "lat,lng"
 * - last_gps as JSON {"lat":..,"lng":..}
 */
class DriverGeoController extends Controller
{
    public function index()
    {
        $rows = Driver::with('lastLocation')->get();

        $data = $rows->map(function ($d) {
            [$lat, $lng] = $this->extractLatLng($d);

            return [
                'id'          => $d->id,
                'name'        => $d->name ?? null,
                'phone'       => $d->phone ?? null,
                'status'      => $d->status ?? 'offline',
                'rating'      => isset($d->rating) ? (float)$d->rating : null,
                'lat'         => $lat,
                'lng'         => $lng,
                'battery'     => $this->extractBattery($d),
                'online'      => $this->isOnline($d),
                'low_battery' => $this->hasLowBattery($d),
                'last_seen_at'=> $this->getLastSeenAt($d),
                'app_version' => $d->app_version ?? null,
                'city'        => $d->city ?? null,
            ];
        })->filter(fn($d) => $d['lat'] !== null && $d['lng'] !== null)->values();

        return response()->json(['data' => $data]);
    }

    private function extractLatLng($driver): array
    {
        // Try to get from lastLocation relationship
        if ($driver->lastLocation) {
            $loc = $driver->lastLocation;
            
            if (isset($loc->latitude, $loc->longitude) && is_numeric($loc->latitude) && is_numeric($loc->longitude)) {
                return [(float)$loc->latitude, (float)$loc->longitude];
            }
            
            if (isset($loc->lat, $loc->lng) && is_numeric($loc->lat) && is_numeric($loc->lng)) {
                return [(float)$loc->lat, (float)$loc->lng];
            }
        }

        // 1) Direct fields on driver
        if (isset($driver->last_lat, $driver->last_lng) && is_numeric($driver->last_lat) && is_numeric($driver->last_lng)) {
            return [(float)$driver->last_lat, (float)$driver->last_lng];
        }

        // 2) last_gps = "lat,lng"
        if (isset($driver->last_gps) && is_string($driver->last_gps) && str_contains($driver->last_gps, ',')) {
            [$a, $b] = array_map('trim', explode(',', $driver->last_gps, 2));
            if (is_numeric($a) && is_numeric($b)) return [(float)$a, (float)$b];
        }

        // 3) last_gps JSON {"lat":..,"lng":..}
        if (isset($driver->last_gps) && is_string($driver->last_gps)) {
            $json = json_decode($driver->last_gps, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($json['lat'],$json['lng'])) {
                return [ (float)$json['lat'], (float)$json['lng'] ];
            }
        }

        return [null, null];
    }

    private function extractBattery($driver): ?int
    {
        if ($driver->lastLocation && isset($driver->lastLocation->battery_level)) {
            return (int)$driver->lastLocation->battery_level;
        }
        
        if (isset($driver->last_battery)) {
            return (int)$driver->last_battery;
        }
        
        return null;
    }

    private function isOnline($driver): bool
    {
        if ($driver->lastLocation && isset($driver->lastLocation->updated_at)) {
            return now()->subMinutes(5)->lte($driver->lastLocation->updated_at);
        }
        
        if (isset($driver->last_seen_at)) {
            return now()->subMinutes(5)->lte($driver->last_seen_at);
        }
        
        return false;
    }

    private function hasLowBattery($driver): bool
    {
        $battery = $this->extractBattery($driver);
        return $battery !== null && $battery < 15;
    }

    private function getLastSeenAt($driver): ?string
    {
        if ($driver->lastLocation && isset($driver->lastLocation->updated_at)) {
            return (string)$driver->lastLocation->updated_at;
        }
        
        if (isset($driver->last_seen_at)) {
            return (string)$driver->last_seen_at;
        }
        
        return null;
    }
}
