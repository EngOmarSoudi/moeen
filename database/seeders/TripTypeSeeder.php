<?php

namespace Database\Seeders;

use App\Models\TripType;
use Illuminate\Database\Seeder;

class TripTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Airport Transfer', 'description' => 'Transportation to/from airport'],
            ['name' => 'City Tour', 'description' => 'Guided city tour'],
            ['name' => 'Hotel Transport', 'description' => 'Hotel to destination transport'],
            ['name' => 'Daily Commute', 'description' => 'Regular daily commute'],
            ['name' => 'Group Charter', 'description' => 'Large group charter service'],
            ['name' => 'Business Travel', 'description' => 'Corporate travel'],
        ];

        foreach ($types as $type) {
            TripType::create($type);
        }
    }
}
