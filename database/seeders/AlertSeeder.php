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
                'trip_id' => $trip->id,
                'title' => 'Trip ' . $trip->code . ' is running late',
                'description' => 'Trip ' . $trip->code . ' is running late',
                'status' => 'new',
            ]);
        }

        // Driver-related alerts
        foreach ($drivers->random(5) as $driver) {
            Alert::create([
                'alert_type_id' => $alertTypes->where('name', 'Document Expiry')->first()->id,
                'driver_id' => $driver->id,
                'title' => 'License expiry approaching for ' . $driver->name,
                'description' => 'License expiry approaching for ' . $driver->name,
                'status' => 'new',
            ]);
        }
    }
}
