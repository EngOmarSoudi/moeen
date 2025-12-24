<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\AlertType;
use App\Models\Trip;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    public function run(): void
    {
        $alertTypes = AlertType::all();
        $trips = Trip::all();
        $drivers = Driver::all();

        // Trip-related alerts
        foreach ($trips->random(10) as $trip) {
            Alert::create([
                'alert_type_id' => $alertTypes->where('name', 'Delay Warning')->first()->id,
                'alertable_id' => $trip->id,
                'alertable_type' => Trip::class,
                'message' => 'Trip ' . $trip->code . ' is running late',
                'severity' => 'medium',
                'status' => 'open',
                'created_at' => now(),
            ]);
        }

        // Driver-related alerts
        foreach ($drivers->random(5) as $driver) {
            Alert::create([
                'alert_type_id' => $alertTypes->where('name', 'Document Expiry')->first()->id,
                'alertable_id' => $driver->id,
                'alertable_type' => Driver::class,
                'message' => 'License expiry approaching for ' . $driver->name,
                'severity' => 'medium',
                'status' => 'open',
                'created_at' => now(),
            ]);
        }
    }
}
