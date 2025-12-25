<?php

use App\Models\Trip;
use App\Models\TripTrackingPoint;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Create or Find an Active Trip
$trip = Trip::create([
    'code' => 'TEST-TRACK-01',
    'status' => 'in_progress',
    'service_kind' => 'airport',
    'origin' => 'King Khaled International Airport',
    'origin_lat' => 24.9618,
    'origin_lng' => 46.7028,
    'destination' => 'Riyadh Park Mall',
    'destination_lat' => 24.7570,
    'destination_lng' => 46.6300,
    'start_at' => now(),
    'passenger_count' => 2,
    'amount' => 150,
    'final_amount' => 150,
    'customer_id' => 1, // Assuming customer 1 exists, if not create one?
    'vehicle_type_id' => 1 // Assuming vehicle type 1 exists
]);

// Ensure dependencies exist if not
if (!$trip->customer_id) {
    echo "Creating dummy customer...\n";
    $customer = \App\Models\Customer::firstOrCreate(
        ['email' => 'test@example.com'],
        ['name' => 'Test Customer', 'phone' => '123456789']
    );
    $trip->customer_id = $customer->id;
}

if (!$trip->vehicle_type_id) {
    echo "Creating dummy vehicle type...\n";
    $type = \App\Models\VehicleType::firstOrCreate(
        ['name' => 'Sedan'],
        ['base_price' => 50]
    );
    $trip->vehicle_type_id = $type->id;
}

$trip->save();

echo "Created Active Trip: {$trip->code} (ID: {$trip->id})\n";

// 2. Add Tracking Point (simulating driver is 20% along the way)
// Coordinates somewhere between Airport and Riyadh Park
$driverLat = 24.9000;
$driverLng = 46.6800;

TripTrackingPoint::create([
    'trip_id' => $trip->id,
    'latitude' => $driverLat,
    'longitude' => $driverLng,
    'heading' => 180, // South
    'speed' => 60,
    'recorded_at' => now(),
]);

echo "Added Tracking Point at {$driverLat}, {$driverLng}\n";
echo "Go to Dashboard to verify map.\n";
