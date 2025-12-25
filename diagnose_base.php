<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $class = \Filament\Resources\Resource::class;
    echo "Checking Base Class: $class\n";
    $reflection = new ReflectionClass($class);
    $method = $reflection->getMethod('form');
    echo "  Method 'form' defined.\n";
    echo "  Parameters:\n";
    foreach ($method->getParameters() as $param) {
         echo "    - " . $param->getType() . " $" . $param->getName() . "\n";
    }
    echo "  Return type: " . $method->getReturnType() . "\n";
} catch (\Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}
