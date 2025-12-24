<?php

namespace Database\Seeders;

use App\Models\CustomerStatus;
use Illuminate\Database\Seeder;

class CustomerStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active', 'description' => 'Customer is actively using services'],
            ['name' => 'Inactive', 'description' => 'Customer account is inactive'],
            ['name' => 'Suspended', 'description' => 'Account suspended due to policy violation'],
            ['name' => 'Pending', 'description' => 'New customer pending verification'],
            ['name' => 'VIP', 'description' => 'Premium customer with special privileges'],
        ];

        foreach ($statuses as $status) {
            CustomerStatus::create($status);
        }
    }
}
