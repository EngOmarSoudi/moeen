<?php

namespace Database\Seeders;

use App\Models\AlertType;
use Illuminate\Database\Seeder;

class AlertTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Safety Issue', 'severity' => 'critical', 'description' => 'Safety-related alert'],
            ['name' => 'Delay Warning', 'severity' => 'warning', 'description' => 'Trip delay alert'],
            ['name' => 'Maintenance Required', 'severity' => 'warning', 'description' => 'Vehicle maintenance alert'],
            ['name' => 'Document Expiry', 'severity' => 'warning', 'description' => 'License or document expiry alert'],
            ['name' => 'Low Balance', 'severity' => 'info', 'description' => 'Wallet balance low'],
            ['name' => 'Payment Pending', 'severity' => 'warning', 'description' => 'Payment pending alert'],
        ];

        foreach ($types as $type) {
            AlertType::create($type);
        }
    }
}
