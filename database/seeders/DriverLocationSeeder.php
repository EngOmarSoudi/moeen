<?php

namespace Database\Seeders;

use App\Models\DriverLastLocation;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverLocationSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = Driver::all();

        // Sample coordinates for Riyadh area
        $locations = [
            ['latitude' => 24.7136, 'longitude' => 46.6753, 'address' => 'Riyadh City Center'],
            ['latitude' => 24.7250, 'longitude' => 46.6220, 'address' => 'Olaya District'],
            ['latitude' => 24.8241, 'longitude' => 46.7708, 'address' => 'King Fahd International Airport'],
            ['latitude' => 24.6500, 'longitude' => 46.7167, 'address' => 'South Riyadh'],
            ['latitude' => 24.8500, 'longitude' => 46.6500, 'address' => 'North Riyadh'],
            ['latitude' => 24.7700, 'longitude' => 46.8000, 'address' => 'East Riyadh'],
            ['latitude' => 24.7000, 'longitude' => 46.5500, 'address' => 'West Riyadh'],
            ['latitude' => 24.7500, 'longitude' => 46.7000, 'address' => 'Diplomatic Quarter'],
        ];

        foreach ($drivers as $driver) {
            $location = $locations[array_rand($locations)];

            DriverLastLocation::create([
                'driver_id' => $driver->id,
                'latitude' => $location['latitude'] + (rand(-100, 100) / 1000),
                'longitude' => $location['longitude'] + (rand(-100, 100) / 1000),
                'speed' => rand(0, 100),
                'heading' => rand(0, 360),
                'address' => $location['address'],
                'recorded_at' => now()->subMinutes(rand(0, 60)),
            ]);
        }
    }
}
