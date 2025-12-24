<?php

namespace Database\Seeders;

use App\Models\WalletTransaction;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $wallets = Wallet::all();
        $types = ['credit', 'debit'];

        foreach ($wallets as $wallet) {
            // Add 2-5 transactions per wallet
            for ($i = 0; $i < rand(2, 5); $i++) {
                $type = $types[array_rand($types)];
                $amount = rand(100, 5000) / 10;

                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $type,
                    'amount' => $amount,
                    'description' => ucfirst($type) . ' transaction ' . ($i + 1),
                    'reference_id' => 'TXN-' . uniqid(),
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
