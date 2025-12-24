<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $staffData = [
            ['job_title' => 'Operations Manager', 'department' => 'Operations', 'salary' => 5000],
            ['job_title' => 'Fleet Manager', 'department' => 'Fleet Management', 'salary' => 5500],
            ['job_title' => 'Finance Manager', 'department' => 'Finance', 'salary' => 5200],
            ['job_title' => 'Customer Service Manager', 'department' => 'CRM', 'salary' => 4500],
            ['job_title' => 'System Administrator', 'department' => 'IT', 'salary' => 4800],
        ];

        foreach ($staffData as $data) {
            Staff::create([
                ...$data,
                'user_id' => $users->random()->id,
                'hired_at' => now()->subMonths(rand(3, 24)),
                'birth_date' => now()->subYears(rand(25, 55)),
                'employee_id' => 'EMP-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'status' => 'active',
            ]);
        }
    }
}
