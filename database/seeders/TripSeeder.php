<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\TripType;
use App\Models\TravelRoute;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $routes = TravelRoute::all();
        $agents = Agent::all();
        $users = User::all();
        $vehicleTypes = \App\Models\VehicleType::all();
        
        $statuses = ['scheduled', 'pending', 'assigned', 'in_progress', 'completed', 'canceled'];

        for ($i = 0; $i < 50; $i++) {
            $status = $statuses[array_rand($statuses)];
            $startTime = now()->addDays(rand(-30, 30))->setHour(rand(6, 22));
            $endTime = $startTime->clone()->addHours(rand(1, 8));
            
            Trip::create([
                'code' => 'TRP-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),

                'customer_id' => $customers->random()->id,
                'vehicle_type_id' => $vehicleTypes->random()->id,
                'travel_route_id' => $routes->random()->id,
                'agent_id' => $agents->random()->id,
                'origin' => 'Riyadh',
                'destination' => 'Jeddah',
                'start_at' => $startTime,
                'completed_at' => $status === 'completed' ? $endTime : null,
                'status' => $status,
                'service_kind' => ['airport', 'hotel', 'city_tour'][array_rand(['airport', 'hotel', 'city_tour'])],
                'customer_segment' => ['new', 'returning'][array_rand(['new', 'returning'])],
                'trip_leg' => ['outbound', 'return'][array_rand(['outbound', 'return'])],
                'passenger_count' => rand(1, 5),
                'amount' => rand(50, 500),
                'discount' => rand(0, 50),
                'final_amount' => rand(50, 500),
                'notes' => 'Trip ' . ($i + 1) . ' sample notes',
                'created_by' => $users->random()->id,
            ]);
        }
    }
}
