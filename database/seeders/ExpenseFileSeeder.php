<?php

namespace Database\Seeders;

use App\Models\ExpenseFile;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseFileSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = Expense::all();

        foreach ($expenses as $expense) {
            // Add 0-2 files per expense
            for ($i = 0; $i < rand(0, 2); $i++) {
                ExpenseFile::create([
                    'expense_id' => $expense->id,
                    'file_path' => 'expenses/receipt-' . uniqid() . '.pdf',
                    'file_name' => 'receipt-' . ($i + 1) . '.pdf',
                    'file_size' => rand(100, 2000),
                ]);
            }
        }
    }
}
