<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerStatus;
use App\Models\Agent;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = CustomerStatus::all();
        $agents = Agent::all();
        $firstNames = ['Ahmed', 'Fatima', 'Mohammad', 'Sarah', 'Khalid', 'Noor', 'Hassan', 'Layla'];
        $lastNames = ['Al-Rashid', 'Al-Dosari', 'Al-Otaibi', 'Al-Qattan', 'Al-Shammari', 'Al-Malik', 'Al-Saad'];
        $documentTypes = ['national_id', 'passport', 'residence_permit', 'driver_license', 'other'];
        $nationalities = ['Saudi', 'Egyptian', 'Jordanian', 'Syrian', 'Lebanese', 'Moroccan'];

        for ($i = 0; $i < 20; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            Customer::create([
                'name' => "$firstName $lastName",
                'email' => strtolower($firstName . '.' . $lastName) . '@customer.com',
                'phone' => '0501' . rand(100000, 999999),
                'nationality' => $nationalities[array_rand($nationalities)],
                'document_type' => $documentTypes[array_rand($documentTypes)],
                'document_no' => rand(100000, 999999),
                'issuing_authority' => 'Government',
                'status_id' => $statuses->random()->id,
                'agent_id' => $agents->random()->id,
                'notes' => 'Customer ' . ($i + 1) . ' sample notes',
            ]);
        }
    }
}
