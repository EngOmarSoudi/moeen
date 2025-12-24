<?php

namespace Database\Seeders;

use App\Models\Wallet;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        // Customer wallets
        $customers = Customer::all();
        foreach ($customers as $customer) {
            Wallet::create([
                'owner_id' => $customer->id,
                'owner_type' => Customer::class,
                'balance' => rand(1000, 50000) / 10,
                'currency' => 'SAR',
                'is_active' => true,
            ]);
        }

        // Driver wallets
        $drivers = Driver::all();
        foreach ($drivers as $driver) {
            Wallet::create([
                'owner_id' => $driver->id,
                'owner_type' => Driver::class,
                'balance' => rand(5000, 100000) / 10,
                'currency' => 'SAR',
                'is_active' => true,
            ]);
        }
    }
}
