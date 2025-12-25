<?php
// Syntax check for all resources
$files = [
    // Batch 1
    'app/Filament/Resources/Trips/Schemas/TripForm.php',
    'app/Filament/Resources/Trips/TripResource.php',
    'app/Filament/Resources/Customers/Schemas/CustomerForm.php',
    'app/Filament/Resources/Customers/CustomerResource.php',
    'app/Filament/Resources/TravelRoutes/Schemas/TravelRouteForm.php',
    'app/Filament/Resources/TravelRoutes/TravelRouteResource.php',
    'app/Filament/Resources/RouteTemplates/RouteTemplateResource.php',
    
    // Batch 2
    'app/Filament/Resources/Drivers/Schemas/DriverForm.php',
    'app/Filament/Resources/Drivers/DriverResource.php',
    'app/Filament/Resources/Vehicles/Schemas/VehicleForm.php',
    'app/Filament/Resources/Vehicles/VehicleResource.php',
    'app/Filament/Resources/VehicleTypes/Schemas/VehicleTypeForm.php',
    'app/Filament/Resources/VehicleTypes/VehicleTypeResource.php',
    'app/Filament/Resources/Staff/Schemas/StaffForm.php',
    'app/Filament/Resources/Staff/StaffResource.php',
];

foreach ($files as $file) {
    try {
        if (!file_exists(__DIR__ . '/' . $file)) {
             echo "MISSING: $file\n";
             continue;
        }
        require_once __DIR__ . '/' . $file;
        echo "OK: $file\n";
    } catch (\Throwable $e) {
        echo "ERROR in $file: " . $e->getMessage() . "\n";
    }
}
