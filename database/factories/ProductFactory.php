<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            ['name' => 'Wireless Headphones', 'category' => 'Audio', 'price_range' => [49.99, 299.99]],
            ['name' => 'Smart Watch', 'category' => 'Wearables', 'price_range' => [149.99, 599.99]],
            ['name' => 'Laptop Stand', 'category' => 'Accessories', 'price_range' => [29.99, 99.99]],
            ['name' => 'Mechanical Keyboard', 'category' => 'Peripherals', 'price_range' => [79.99, 249.99]],
            ['name' => 'USB-C Hub', 'category' => 'Accessories', 'price_range' => [29.99, 89.99]],
            ['name' => 'Wireless Mouse', 'category' => 'Peripherals', 'price_range' => [19.99, 129.99]],
            ['name' => 'Portable SSD', 'category' => 'Storage', 'price_range' => [69.99, 299.99]],
            ['name' => 'Webcam HD', 'category' => 'Accessories', 'price_range' => [49.99, 149.99]],
            ['name' => 'Phone Case', 'category' => 'Mobile', 'price_range' => [9.99, 49.99]],
            ['name' => 'Bluetooth Speaker', 'category' => 'Audio', 'price_range' => [39.99, 199.99]],
            ['name' => 'Gaming Monitor', 'category' => 'Displays', 'price_range' => [199.99, 799.99]],
            ['name' => 'Tablet', 'category' => 'Devices', 'price_range' => [299.99, 899.99]],
            ['name' => 'Power Bank', 'category' => 'Accessories', 'price_range' => [19.99, 79.99]],
            ['name' => 'Desk Lamp', 'category' => 'Office', 'price_range' => [29.99, 89.99]],
            ['name' => 'Ergonomic Chair', 'category' => 'Furniture', 'price_range' => [199.99, 599.99]],
            ['name' => 'Cable Organizer', 'category' => 'Accessories', 'price_range' => [9.99, 29.99]],
            ['name' => 'External Hard Drive', 'category' => 'Storage', 'price_range' => [59.99, 199.99]],
            ['name' => 'Screen Protector', 'category' => 'Mobile', 'price_range' => [9.99, 29.99]],
            ['name' => 'Laptop Bag', 'category' => 'Accessories', 'price_range' => [29.99, 99.99]],
            ['name' => 'Charging Cable', 'category' => 'Accessories', 'price_range' => [9.99, 29.99]],
        ];

        $product = fake()->randomElement($products);
        $variant = fake()->randomElement(['Pro', 'Plus', 'Elite', 'Premium', 'Standard', 'Basic', 'Max', 'Mini', 'Ultra']);

        // Generate random image from Unsplash for product category
        $imageCategories = [
            'Audio' => 'headphones',
            'Wearables' => 'smartwatch',
            'Accessories' => 'tech-accessories',
            'Peripherals' => 'keyboard',
            'Storage' => 'hard-drive',
            'Mobile' => 'phone',
            'Displays' => 'monitor',
            'Devices' => 'tablet',
            'Office' => 'desk',
            'Furniture' => 'chair',
        ];

        $imageKeyword = $imageCategories[$product['category']] ?? 'technology';
        $imageId = fake()->numberBetween(1, 100);

        return [
            'name' => $product['name'] . ' ' . $variant,
            'description' => fake()->sentence(12),
            'price' => fake()->randomFloat(2, $product['price_range'][0], $product['price_range'][1]),
            'stock_quantity' => fake()->numberBetween(0, 200),
            'image_url' => "https://picsum.photos/seed/{$imageKeyword}{$imageId}/800/600",
        ];
    }
}
