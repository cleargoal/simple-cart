<?php

namespace App\Actions\Cart;

use App\Exceptions\Cart\InsufficientStockException;
use App\Exceptions\Cart\InvalidQuantityException;
use App\Models\CartItem;
use App\Models\User;

class UpdateCartItem
{
    /**
     * Update cart item quantity
     * @throws InvalidQuantityException
     * @throws InsufficientStockException
     */
    public function execute(User $user, int $cartItemId, int $quantity): CartItem
    {
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->with('product')
            ->firstOrFail();

        if ($quantity <= 0) {
            throw new InvalidQuantityException('Quantity must be greater than 0');
        }

        // Check stock availability
        if (!$cartItem->product->isInStock($quantity)) {
            throw new InsufficientStockException("Insufficient stock. Only {$cartItem->product->stock_quantity} items available.");
        }

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->fresh('product');
    }
}
