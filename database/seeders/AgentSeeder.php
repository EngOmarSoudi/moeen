<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            ['name' => 'Riyadh Travel Agency', 'company_name' => 'Riyadh Travel Agency', 'commission_type' => 'percentage', 'commission_value' => 5.0],
            ['name' => 'Jeddah Tours', 'company_name' => 'Jeddah Tours', 'commission_type' => 'percentage', 'commission_value' => 7.5],
            ['name' => 'Dammam Transport', 'company_name' => 'Dammam Transport', 'commission_type' => 'percentage', 'commission_value' => 6.0],
            ['name' => 'Abha Holiday Tours', 'company_name' => 'Abha Holiday Tours', 'commission_type' => 'percentage', 'commission_value' => 8.0],
            ['name' => 'Corporate Travel Solutions', 'company_name' => 'Corporate Travel Solutions', 'commission_type' => 'percentage', 'commission_value' => 4.5],
        ];

        foreach ($agents as $agent) {
            Agent::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $agent['name'])) . '@agents.com'],
                [
                    'name' => $agent['name'],
                    'company_name' => $agent['company_name'],
                    'commission_type' => $agent['commission_type'],
                    'commission_value' => $agent['commission_value'],
                    'phone' => '0501' . rand(100000, 999999),
                    'credit_limit' => rand(10000, 50000),
                    'credit_used' => rand(0, 10000),
                    'status' => 'active',
                ]
            );
        }
    }
}
