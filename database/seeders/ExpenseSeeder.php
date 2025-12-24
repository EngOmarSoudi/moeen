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
            $ownerType = rand(0, 1) === 0 ? Driver::class : Vehicle::class;
            $ownerId = $ownerType === Driver::class ? $drivers->random()->id : $vehicles->random()->id;

            Expense::create([
                'category' => $categories[array_rand($categories)],
                'description' => 'Expense ' . ($i + 1),
                'amount' => rand(50, 500),
                'currency' => 'SAR',
                'expense_date' => now()->subDays(rand(0, 30)),
                'owner_id' => $ownerId,
                'owner_type' => $ownerType,
                'receipt_number' => 'RCP-' . rand(100000, 999999),
                'notes' => 'Sample expense entry',
            ]);
        }
    }
}
