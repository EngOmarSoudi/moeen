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
                'walletable_id' => $customer->id,
                'walletable_type' => Customer::class,
                'balance' => rand(1000, 50000) / 10,
                'total_debt' => 0,
            ]);
        }

        // Driver wallets
        $drivers = Driver::all();
        foreach ($drivers as $driver) {
            Wallet::create([
                'walletable_id' => $driver->id,
                'walletable_type' => Driver::class,
                'balance' => rand(5000, 100000) / 10,
                'total_collected' => 0,
            ]);
        }
    }
}
