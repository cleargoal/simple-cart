<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for notifications
        User::factory()->withoutTwoFactor()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create test user without 2FA
        User::factory()->withoutTwoFactor()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 50 sample products
        Product::factory()->count(50)->create();
    }
}
