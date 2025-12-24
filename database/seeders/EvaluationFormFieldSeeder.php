<?php

namespace Database\Seeders;

use App\Models\EvaluationFormField;
use App\Models\EvaluationForm;
use Illuminate\Database\Seeder;

class EvaluationFormFieldSeeder extends Seeder
{
    public function run(): void
    {
        $forms = EvaluationForm::all();
        $fieldTypes = ['text', 'textarea', 'rating', 'checkbox', 'select'];

        foreach ($forms as $form) {
            // Add 3-5 fields per form
            for ($i = 0; $i < rand(3, 5); $i++) {
                EvaluationFormField::create([
                    'evaluation_form_id' => $form->id,
                    'label' => 'Field ' . ($i + 1) . ' for ' . $form->title,
                    'field_type' => $fieldTypes[array_rand($fieldTypes)],
                    'required' => true,
                    'order' => $i + 1,
                ]);
            }
        }
    }
}
