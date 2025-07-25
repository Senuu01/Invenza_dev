<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'last_login' => now()->subHours(2),
            'email_verified_at' => now()->subDays(30),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'last_login' => now()->subHours(3),
            'email_verified_at' => now()->subDays(25),
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'inactive',
            'last_login' => now()->subDays(5)->setTime(4, 45, 0),
            'email_verified_at' => now()->subDays(60),
        ]);

        User::create([
            'name' => 'Sarah Wilson',
            'email' => 'sarah.wilson@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
            'last_login' => now()->subDays(10)->setTime(11, 20, 0),
            'email_verified_at' => now()->subDays(45),
        ]);
    }
}