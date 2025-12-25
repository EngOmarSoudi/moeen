<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;

try {
    echo "Trips columns: " . implode(', ', Schema::getColumnListing('trips')) . PHP_EOL;
    echo "TravelRoutes columns: " . implode(', ', Schema::getColumnListing('travel_routes')) . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
