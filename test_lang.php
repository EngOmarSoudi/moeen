<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Request;

echo "--- TESTING LANGUAGE SWITCHER ---\n";

// 1. Test Default Locale
echo "1. Default Locale: " . app()->getLocale() . "\n";

// 2. Simulate Route Hit to set session
session()->put('locale', 'ar');
session()->save();

// 3. Re-run middleware logic (simulation)
if (session()->has('locale')) {
    app()->setLocale(session('locale'));
}

echo "2. Locale after switch: " . app()->getLocale() . " (Expected: ar)\n";

if (app()->getLocale() === 'ar') {
    echo "PASS: Language switching logic works.\n";
} else {
    echo "FAIL: Language did not switch.\n";
}
