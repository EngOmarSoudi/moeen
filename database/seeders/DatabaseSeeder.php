<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Order matters - dependencies first
        $this->call([
            UserSeeder::class,
            StaffSeeder::class,
            CustomerStatusSeeder::class,
            VehicleTypeSeeder::class,
            TripTypeSeeder::class,
            AlertTypeSeeder::class,
            AgentSeeder::class,
            CustomerSeeder::class,
            CustomerRelativeSeeder::class,
            DriverSeeder::class,
            VehicleSeeder::class,
            DriverShiftSeeder::class,
            TravelRouteSeeder::class,
            WalletSeeder::class,
            TripSeeder::class,
            TripCustomerSeeder::class,
            PaymentCollectionSeeder::class,
            ExpenseSeeder::class,
            ExpenseFileSeeder::class,
            AlertSeeder::class,
            WalletTransactionSeeder::class,
            DriverLocationSeeder::class,
            ReportSeeder::class,
            ReportMessageSeeder::class,
            EvaluationFormSeeder::class,
            EvaluationFormFieldSeeder::class,
            TripEvaluationSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
