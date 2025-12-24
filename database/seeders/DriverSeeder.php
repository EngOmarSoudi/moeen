<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $firstNames = ['Ahmed', 'Fatima', 'Mohammad', 'Hassan', 'Khalid', 'Ibrahim', 'Youssef', 'Omar'];
        $lastNames = ['Al-Rashid', 'Al-Dosari', 'Al-Otaibi', 'Al-Shammari', 'Al-Malik', 'Al-Saad', 'Al-Qattan'];

        for ($i = 0; $i < 15; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            
            Driver::create([
                'name' => "$firstName $lastName",
                'email' => strtolower($firstName . '.' . $lastName) . '@driver.com',
                'phone' => '0501' . rand(100000, 999999),
                'license_number' => 'DL' . rand(100000, 999999),
                'license_expiry' => now()->addYears(rand(1, 5)),
                'id_number' => rand(1000000000, 9999999999),
                'status' => ['available', 'busy', 'offline'][rand(0, 2)],
                'rating' => rand(35, 50) / 10,
                'total_trips' => rand(10, 500),
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
