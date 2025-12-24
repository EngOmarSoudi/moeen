<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicleTypes = VehicleType::all();
        $brands = ['Toyota', 'Honda', 'Nissan', 'Hyundai', 'KIA', 'BMW', 'Mercedes', 'Lexus'];
        $colors = ['White', 'Black', 'Silver', 'Gray', 'Blue', 'Red', 'Gold', 'Beige'];

        for ($i = 0; $i < 20; $i++) {
            $year = rand(2018, 2024);
            
            Vehicle::create([
                'plate_number' => strtoupper(chr(rand(65, 90)) . chr(rand(65, 90))) . rand(100, 999),
                'vehicle_type_id' => $vehicleTypes->random()->id,
                'model' => $brands[array_rand($brands)] . ' Model ' . rand(100, 500),
                'color' => $colors[array_rand($colors)],
                'vin' => 'VIN' . rand(10000000, 99999999),
                'year' => $year,
                'registration_expiry' => now()->addYears(rand(1, 5)),
                'insurance_expiry' => now()->addYears(rand(1, 3)),
                'status' => ['active', 'maintenance'][rand(0, 1)],
                'notes' => 'Vehicle ' . ($i + 1) . ' sample notes',
            ]);
        }
    }
}
