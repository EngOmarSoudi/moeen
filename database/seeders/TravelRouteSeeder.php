<?php

namespace Database\Seeders;

use App\Models\TravelRoute;
use Illuminate\Database\Seeder;

class TravelRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            ['name' => 'Riyadh to Jeddah', 'origin' => 'Riyadh', 'destination' => 'Jeddah', 'distance_km' => 950, 'duration_minutes' => 570],
            ['name' => 'Riyadh to Dammam', 'origin' => 'Riyadh', 'destination' => 'Dammam', 'distance_km' => 400, 'duration_minutes' => 270],
            ['name' => 'Jeddah to Mecca', 'origin' => 'Jeddah', 'destination' => 'Mecca', 'distance_km' => 80, 'duration_minutes' => 90],
            ['name' => 'Riyadh to Abha', 'origin' => 'Riyadh', 'destination' => 'Abha', 'distance_km' => 1200, 'duration_minutes' => 720],
            ['name' => 'Dammam to Khobar', 'origin' => 'Dammam', 'destination' => 'Khobar', 'distance_km' => 30, 'duration_minutes' => 30],
            ['name' => 'Riyadh Airport to City Center', 'origin' => 'King Fahd International Airport', 'destination' => 'Riyadh City Center', 'distance_km' => 35, 'duration_minutes' => 45],
            ['name' => 'Downtown Riyadh Circuit', 'origin' => 'Olaya District', 'destination' => 'Kingdom Centre', 'distance_km' => 15, 'duration_minutes' => 30],
        ];

        foreach ($routes as $route) {
            TravelRoute::create([
                ...$route,
                'route_type' => 'one_way',
                'is_active' => true,
            ]);
        }
    }
}
