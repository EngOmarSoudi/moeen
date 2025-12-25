<?php
// Syntax check
$files = [
    'app/Filament/Resources/Drivers/Schemas/DriverForm.php',
    'app/Filament/Resources/Vehicles/Schemas/VehicleForm.php',
    'app/Filament/Resources/VehicleTypes/Schemas/VehicleTypeForm.php',
    'app/Filament/Resources/Staff/Schemas/StaffForm.php',
    'app/Filament/Resources/Drivers/DriverResource.php',
    'app/Filament/Resources/Vehicles/VehicleResource.php',
    'app/Filament/Resources/VehicleTypes/VehicleTypeResource.php',
    'app/Filament/Resources/Staff/StaffResource.php',
];

foreach ($files as $file) {
    try {
        require_once __DIR__ . '/' . $file;
        echo "OK: $file\n";
    } catch (\Throwable $e) {
        echo "ERROR in $file: " . $e->getMessage() . "\n";
    }
}
