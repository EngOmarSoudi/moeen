<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Sedan', 'description' => '4-seater sedan', 'capacity' => 4],
            ['name' => 'SUV', 'description' => 'Sport Utility Vehicle', 'capacity' => 7],
            ['name' => 'Bus', 'description' => 'Large bus for group transport', 'capacity' => 50],
            ['name' => 'Minibus', 'description' => 'Mid-size van', 'capacity' => 14],
            ['name' => 'Limousine', 'description' => 'Luxury vehicle', 'capacity' => 4],
        ];

        foreach ($types as $type) {
            VehicleType::create($type);
        }
    }
}
