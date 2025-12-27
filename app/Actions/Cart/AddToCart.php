<?php

namespace App\Actions\Cart;

use App\Exceptions\Cart\InsufficientStockException;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class AddToCart
{
    /**
     * Add a product to the user's cart
     * @throws InsufficientStockException
     */
    public function execute(User $user, int $productId, int $quantity = 1): CartItem
    {
        $product = Product::findOrFail($productId);

        // Check if product has enough stock
        if (!$product->isInStock($quantity)) {
            throw new InsufficientStockException("Insufficient stock. Only {$product->stock_quantity} items available.");
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $newQuantity = $cartItem->quantity + $quantity;

            if (!$product->isInStock($newQuantity)) {
                throw new InsufficientStockException("Cannot add more items. Only {$product->stock_quantity} items available.");
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem->load('product');
    }
}
