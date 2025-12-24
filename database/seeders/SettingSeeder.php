<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'MOEAN Transportation Management System', 'description' => 'Application name'],
            ['key' => 'company_name', 'value' => 'MOEAN Transportation', 'description' => 'Company name'],
            ['key' => 'company_phone', 'value' => '+966-11-1234567', 'description' => 'Company phone number'],
            ['key' => 'company_email', 'value' => 'info@moean.com', 'description' => 'Company email'],
            ['key' => 'company_address', 'value' => 'Riyadh, Saudi Arabia', 'description' => 'Company address'],
            ['key' => 'currency', 'value' => 'SAR', 'description' => 'System currency'],
            ['key' => 'timezone', 'value' => 'Asia/Riyadh', 'description' => 'System timezone'],
            ['key' => 'max_trip_distance', 'value' => '2000', 'description' => 'Maximum trip distance in km'],
            ['key' => 'base_fare', 'value' => '50', 'description' => 'Base fare in SAR'],
            ['key' => 'distance_rate', 'value' => '2.5', 'description' => 'Rate per km in SAR'],
            ['key' => 'maintenance_reminder_days', 'value' => '30', 'description' => 'Days before maintenance reminder'],
            ['key' => 'license_expiry_reminder_days', 'value' => '60', 'description' => 'Days before license expiry reminder'],
            ['key' => 'support_email', 'value' => 'support@moean.com', 'description' => 'Support email'],
            ['key' => 'notification_enabled', 'value' => 'true', 'description' => 'Enable notifications'],
            ['key' => 'language', 'value' => 'en', 'description' => 'Default language'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
