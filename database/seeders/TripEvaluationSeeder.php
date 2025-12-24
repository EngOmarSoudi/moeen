<?php

namespace Database\Seeders;

use App\Models\TripEvaluation;
use App\Models\Trip;
use App\Models\EvaluationForm;
use Illuminate\Database\Seeder;

class TripEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $completedTrips = Trip::where('status', 'completed')->get();
        $forms = EvaluationForm::all();

        foreach ($completedTrips->random(min(10, $completedTrips->count())) as $trip) {
            TripEvaluation::create([
                'trip_id' => $trip->id,
                'evaluation_form_id' => $forms->random()->id,
                'rating' => rand(3, 5),
                'feedback' => 'Sample evaluation feedback for trip ' . $trip->code,
                'evaluator_name' => 'Customer ' . rand(1, 100),
                'created_at' => $trip->completed_at ?? now(),
            ]);
        }
    }
}
