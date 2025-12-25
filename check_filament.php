<?php
require 'vendor/autoload.php';
try {
    $r = new ReflectionClass('App\Filament\Resources\Trips\Pages\ViewTrip');
    echo "Properties: ";
    foreach($r->getProperties() as $p) {
        echo $p->getName() . " (" . ($p->isPublic() ? 'public' : 'non-public') . "), ";
    }
    echo PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
