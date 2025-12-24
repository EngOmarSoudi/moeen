<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\DriverShift;
use Illuminate\Database\Seeder;

class DriverShiftSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = Driver::all();

        foreach ($drivers as $driver) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $startTime = rand(0, 23) . ':00';
                $endHour = (int)explode(':', $startTime)[0] + rand(6, 10);
                $endTime = ($endHour % 24) . ':00';
                
                DriverShift::create([
                    'driver_id' => $driver->id,
                    'date' => now()->addDays(rand(0, 30)),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_available' => true,
                    'notes' => 'Shift ' . ($i + 1) . ' notes',
                ]);
            }
        }
    }
}
