<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create staff user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'staff@inventory.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Create additional staff users
        User::firstOrCreate(
            ['email' => 'manager@inventory.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'assistant@inventory.com'],
            [
                'name' => 'Assistant User',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
} 