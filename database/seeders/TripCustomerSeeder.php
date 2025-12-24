<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class TripCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $trips = Trip::all();
        $customers = Customer::all();

        foreach ($trips as $trip) {
            // Add 1-3 additional customers to each trip
            $additionalCustomers = $customers->random(rand(0, 3))->pluck('id')->toArray();
            
            if (!empty($additionalCustomers)) {
                $trip->customers()->sync($additionalCustomers);
            }
        }
    }
}
