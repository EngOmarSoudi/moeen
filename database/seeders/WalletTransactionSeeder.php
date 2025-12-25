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

                $balanceBefore = $wallet->balance;
                $balanceAfter = $type === 'credit' ? $balanceBefore + $amount : $balanceBefore - $amount;
                
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $type,
                    'amount' => $amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'description' => ucfirst($type) . ' transaction ' . ($i + 1),
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
