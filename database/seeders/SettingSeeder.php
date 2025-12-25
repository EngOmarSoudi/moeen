<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'MOEAN Transportation Management System', 'group' => 'general', 'type' => 'string'],
            ['key' => 'company_name', 'value' => 'MOEAN Transportation', 'group' => 'general', 'type' => 'string'],
            ['key' => 'company_phone', 'value' => '+966-11-1234567', 'group' => 'general', 'type' => 'string'],
            ['key' => 'company_email', 'value' => 'info@moean.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'company_address', 'value' => 'Riyadh, Saudi Arabia', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency', 'value' => 'SAR', 'group' => 'finance', 'type' => 'string'],
            ['key' => 'timezone', 'value' => 'Asia/Riyadh', 'group' => 'general', 'type' => 'string'],
            ['key' => 'max_trip_distance', 'value' => '2000', 'group' => 'system', 'type' => 'integer'],
            ['key' => 'base_fare', 'value' => '50', 'group' => 'finance', 'type' => 'integer'],
            ['key' => 'distance_rate', 'value' => '2.5', 'group' => 'finance', 'type' => 'string'],
            ['key' => 'maintenance_reminder_days', 'value' => '30', 'group' => 'system', 'type' => 'integer'],
            ['key' => 'license_expiry_reminder_days', 'value' => '60', 'group' => 'system', 'type' => 'integer'],
            ['key' => 'support_email', 'value' => 'support@moean.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'notification_enabled', 'value' => 'true', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'language', 'value' => 'en', 'group' => 'general', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
