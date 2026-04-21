<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@petroflow.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@petroflow.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create operator user
        User::firstOrCreate(
            ['email' => 'operator@petroflow.com'],
            [
                'name' => 'Operator User',
                'email' => 'operator@petroflow.com',
                'password' => Hash::make('operator123'),
                'role' => 'operator',
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
            ]
        );

        // Call other seeders
        $this->call(ProductSeeder::class);
    }
}

