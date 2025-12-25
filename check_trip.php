<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trip = App\Models\Trip::find(52);
if ($trip) {
    echo "Trip 52 data:\n";
    echo "  origin: " . ($trip->origin ?? 'NULL') . "\n";
    echo "  origin_lat: " . ($trip->origin_lat ?? 'NULL') . "\n";
    echo "  origin_lng: " . ($trip->origin_lng ?? 'NULL') . "\n";
    echo "  destination: " . ($trip->destination ?? 'NULL') . "\n";
    echo "  destination_lat: " . ($trip->destination_lat ?? 'NULL') . "\n";
    echo "  destination_lng: " . ($trip->destination_lng ?? 'NULL') . "\n";
    echo "  status: " . ($trip->status ?? 'NULL') . "\n";
} else {
    echo "Trip 52 not found\n";
}
