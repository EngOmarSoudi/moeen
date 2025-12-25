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
                'reference_no' => 'REP-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'subject' => $types[array_rand($types)] . ' - ' . ($i + 1),
                'description' => 'Sample report description',
                'type' => 'complaint',
                'created_by' => $users->random()->id,
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
            ]);
        }
    }
}
