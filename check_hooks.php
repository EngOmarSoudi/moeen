<?php
require 'vendor/autoload.php';
try {
    $r = new ReflectionClass('Filament\View\PanelsRenderHook');
    foreach($r->getConstants() as $name => $value) {
        if (str_contains($name, 'HEAD') || str_contains($name, 'STYLES') || str_contains($name, 'SCRIPTS')) {
            echo "Hook: $name ($value)\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
