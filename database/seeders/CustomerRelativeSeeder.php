<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerRelative;
use Illuminate\Database\Seeder;

class CustomerRelativeSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $relationships = ['family', 'friend', 'company', 'other'];

        foreach ($customers as $customer) {
            // Add 1-3 relatives per customer
            for ($i = 0; $i < rand(1, 3); $i++) {
                CustomerRelative::create([
                    'customer_id' => $customer->id,
                    'name' => 'Relative ' . ($i + 1) . ' of ' . $customer->name,
                    'relationship' => $relationships[array_rand($relationships)],
                    'phone' => '0501' . rand(100000, 999999),
                    'notes' => 'Contact note for relative ' . ($i + 1),
                ]);
            }
        }
    }
}
