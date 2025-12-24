<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $types = ['Daily Summary', 'Weekly Performance', 'Monthly Revenue', 'Driver Statistics', 'Vehicle Usage', 'Customer Analysis'];

        for ($i = 0; $i < 15; $i++) {
            Report::create([
                'title' => $types[array_rand($types)] . ' - ' . ($i + 1),
                'description' => 'Sample report description',
                'type' => 'standard',
                'period_start' => now()->subDays(rand(1, 30))->startOfDay(),
                'period_end' => now()->endOfDay(),
                'created_by' => $users->random()->id,
                'content' => json_encode(['data' => 'Sample report data']),
                'is_published' => true,
            ]);
        }
    }
}
