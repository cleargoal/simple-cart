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
        // Create test user without 2FA
        User::factory()->withoutTwoFactor()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample products
        Product::create([
            'name' => 'Wireless Headphones',
            'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.',
            'price' => 149.99,
            'stock_quantity' => 25,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Smart Watch',
            'description' => 'Feature-rich smartwatch with fitness tracking, heart rate monitor, and GPS.',
            'price' => 299.99,
            'stock_quantity' => 15,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Laptop Stand',
            'description' => 'Ergonomic aluminum laptop stand with adjustable height and angle.',
            'price' => 49.99,
            'stock_quantity' => 50,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Mechanical Keyboard',
            'description' => 'RGB backlit mechanical keyboard with Cherry MX switches and programmable keys.',
            'price' => 129.99,
            'stock_quantity' => 8,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'USB-C Hub',
            'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, SD card reader, and power delivery.',
            'price' => 39.99,
            'stock_quantity' => 100,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Wireless Mouse',
            'description' => 'Ergonomic wireless mouse with precision tracking and long battery life.',
            'price' => 29.99,
            'stock_quantity' => 75,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Portable SSD',
            'description' => '1TB portable SSD with blazing fast read/write speeds and rugged design.',
            'price' => 89.99,
            'stock_quantity' => 30,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Webcam HD',
            'description' => '1080p HD webcam with autofocus and built-in microphone for video calls.',
            'price' => 69.99,
            'stock_quantity' => 5,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Phone Case',
            'description' => 'Protective phone case with shock absorption and wireless charging support.',
            'price' => 19.99,
            'stock_quantity' => 200,
            'image_url' => null,
        ]);

        Product::create([
            'name' => 'Bluetooth Speaker',
            'description' => 'Portable Bluetooth speaker with 360° sound and waterproof design.',
            'price' => 79.99,
            'stock_quantity' => 40,
            'image_url' => null,
        ]);
    }
}
