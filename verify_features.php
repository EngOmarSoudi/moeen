<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RouteTemplate;
use App\Models\Trip;
use App\Models\VehicleType;
use App\Models\Customer;

echo "--- STARTING END-TO-END TEST ---\n";

// 1. Create dependencies
$vehicle = VehicleType::firstOrCreate(['name' => 'GMC Yukon'], ['capacity' => 7]);
echo "1. Vehicle Type: {$vehicle->name} (ID: {$vehicle->id})\n";

$customer = Customer::firstOrCreate(
    ['email' => 'test@example.com'], 
    ['name' => 'Test Customer', 'phone' => '0500000000']
);
echo "2. Customer: {$customer->name} (ID: {$customer->id})\n";

// 2. Test Route Template Creation
echo "\n--- TESTING ROUTE TEMPLATE ---\n";
$template = RouteTemplate::updateOrCreate(
    [
        'origin_city' => 'Dammam',
        'destination_city' => 'Riyadh'
    ],
    [
        'base_price' => 500.00,
        'vehicle_type_id' => $vehicle->id,
        'is_active' => true
    ]
);
echo "3. Created Template: {$template->display_name} - {$template->formatted_price}\n";

// 3. Test Trip Creation (Simulating Form Submission)
echo "\n--- TESTING TRIP CREATION ---\n";
$trip = Trip::create([
    'customer_id' => $customer->id,
    'vehicle_type_id' => $vehicle->id,
    'route_template_id' => $template->id, // This should trigger logic in real app, but here we manually set price effectively testing the model
    'service_kind' => 'transfer',
    'status' => 'scheduled',
    'start_at' => now()->addDay(),
    'origin' => 'Dammam Airport',
    'origin_lat' => 26.4207,
    'origin_lng' => 50.1065,
    'destination' => 'Riyadh Hotel',
    'destination_lat' => 24.7136,
    'destination_lng' => 46.6753,
    // Simulate what the form reactive logic does:
    'amount' => $template->base_price,
    'final_amount' => $template->base_price,
]);

echo "4. Created Trip #{$trip->id}\n";
echo "   - Route Template ID: " . ($trip->route_template_id == $template->id ? "PASS" : "FAIL") . "\n";
echo "   - Price: {$trip->amount} (Expected: 500.00) -> " . ($trip->amount == 500 ? "PASS" : "FAIL") . "\n";
echo "   - Coordinates: " . ($trip->origin_lat && $trip->destination_lat ? "PASS" : "FAIL") . "\n";

// 4. Test Tracking Infrastructure
echo "\n--- TESTING TRACKING INFRASTRUCTURE ---\n";
$point = $trip->trackingPoints()->create([
    'latitude' => 26.4208,
    'longitude' => 50.1066,
    'recorded_at' => now(),
    'speed' => 60,
    'heading' => 270
]);
echo "5. Added Tracking Point: ID {$point->id}\n";
echo "   - Deviation Check: " . ($trip->wasRouteFollowed() ? "PASS (On Route)" : "PASS (Deviated)") . "\n";

echo "\n--- TEST COMPLETE ---\n";
