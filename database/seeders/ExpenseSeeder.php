<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = Driver::all();
        $vehicles = Vehicle::all();
        $categories = ['Fuel', 'Maintenance', 'Repair', 'Insurance', 'Toll', 'Parking', 'Cleaning'];

        for ($i = 0; $i < 30; $i++) {
            Expense::create([
                'category' => $categories[array_rand($categories)],
                'description' => 'Expense ' . ($i + 1),
                'amount' => rand(50, 500),
                'expense_date' => now()->subDays(rand(0, 30)),
                'driver_id' => $drivers->random()->id,
                'vehicle_id' => $vehicles->random()->id,
            ]);
        }
    }
}
