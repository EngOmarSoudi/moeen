<?php

namespace Database\Seeders;

use App\Models\PaymentCollection;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class PaymentCollectionSeeder extends Seeder
{
    public function run(): void
    {
        $trips = Trip::where('is_paid', true)->get();

        foreach ($trips as $trip) {
            PaymentCollection::create([
                'trip_id' => $trip->id,
                'amount' => $trip->final_amount,
                'payment_method' => ['cash', 'card', 'transfer'][array_rand(['cash', 'card', 'transfer'])],
                'reference_number' => 'PAY-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'collected_at' => $trip->completed_at ?? now(),
                'notes' => 'Payment for trip ' . $trip->code,
            ]);
        }
    }
}
