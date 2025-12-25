<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;

try {
    echo "--- Trips Table Columns ---\n";
    foreach (Schema::getColumnListing('trips') as $col) {
        echo $col . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
