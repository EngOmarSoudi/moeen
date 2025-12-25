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
                'target_type' => 'App\\Models\\VehicleType',
                'target_id' => $trip->vehicle_type_id,
                'score' => rand(300, 500) / 100, // Between 3.00 and 5.00
                'answers' => json_encode(['field_1' => rand(3, 5), 'field_2' => rand(3, 5), 'field_3' => rand(3, 5)]),
                'comments' => 'Sample evaluation feedback for trip ' . $trip->code,
            ]);
        }
    }
}
