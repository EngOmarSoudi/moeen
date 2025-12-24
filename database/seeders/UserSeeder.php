<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // Create admin user and assign super_admin role
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@moean.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole('super_admin');

        // Create manager user and assign manager role
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@moean.com'],
            [
                'name' => 'Manager User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
        $managerUser->assignRole('manager');

        User::factory(8)->create();
    }
}
