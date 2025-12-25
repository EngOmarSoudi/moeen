<?php

use App\Models\Trip;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Trip relationship 'latestTrackingPoint'...\n";
    $trips = Trip::with(['latestTrackingPoint'])->limit(5)->get();
    echo "Successfully fetched " . $trips->count() . " trips.\n";
    
    foreach ($trips as $trip) {
        echo "Trip ID: {$trip->id}, Last Point: " . ($trip->latestTrackingPoint ? 'Found' : 'Null') . "\n";
    }
    
    echo "PASS: Relation exists and query works.\n";
} catch (\Exception $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
    exit(1);
}
