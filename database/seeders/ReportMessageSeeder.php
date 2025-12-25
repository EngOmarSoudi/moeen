<?php

namespace Database\Seeders;

use App\Models\ReportMessage;
use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportMessageSeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            // Add 1-3 messages per report
            for ($i = 0; $i < rand(1, 3); $i++) {
                ReportMessage::create([
                    'report_id' => $report->id,
                    'message' => 'Message ' . ($i + 1) . ' for report ' . $report->subject,
                    'is_internal' => rand(0, 1),
                ]);
            }
        }
    }
}
