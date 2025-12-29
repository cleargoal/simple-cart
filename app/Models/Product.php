<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
    ];

    protected $casts = [
        'price' => \App\Casts\Money::class,
    ];

    /**
     * Get cart items for this product
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Check if stock is low (less than threshold)
     */
    public function isLowStock(int $threshold = 10): bool
    {
        return $this->stock_quantity < $threshold && $this->stock_quantity > 0;
    }

    /**
     * Decrease stock quantity
     */
    public function decreaseStock(int $quantity): bool
    {
        if (!$this->isInStock($quantity)) {
            return false;
        }

        $this->decrement('stock_quantity', $quantity);
        return true;
    }
}
