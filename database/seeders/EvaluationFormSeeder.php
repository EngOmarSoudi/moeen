<?php

namespace Database\Seeders;

use App\Models\EvaluationForm;
use Illuminate\Database\Seeder;

class EvaluationFormSeeder extends Seeder
{
    public function run(): void
    {
        $forms = [
            ['title' => 'Driver Performance Evaluation', 'description' => 'Evaluate driver performance and safety', 'type' => 'driver'],
            ['title' => 'Vehicle Condition Check', 'description' => 'Assess vehicle condition and maintenance needs', 'type' => 'vehicle'],
            ['title' => 'Customer Satisfaction Survey', 'description' => 'Gather customer feedback on service quality', 'type' => 'trip'],
            ['title' => 'Trip Quality Assessment', 'description' => 'Evaluate overall trip quality and compliance', 'type' => 'trip'],
        ];

        foreach ($forms as $form) {
            EvaluationForm::create($form);
        }
    }
}
