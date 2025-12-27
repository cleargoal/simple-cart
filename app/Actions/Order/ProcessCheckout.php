<?php

namespace App\Actions\Order;

use App\Exceptions\Cart\EmptyCartException;
use App\Exceptions\Cart\InsufficientStockException;
use App\Jobs\LowStockNotification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProcessCheckout
{
    /**
     * Process checkout and create order
     * @throws EmptyCartException
     * @throws InsufficientStockException
     */
    public function execute(User $user): Order
    {
        return DB::transaction(function () use ($user) {
            // Load cart items with products
            $cartItems = $user->cartItems()->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw new EmptyCartException('Cart is empty');
            }

            // Validate stock availability for all items
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->product->isInStock($cartItem->quantity)) {
                    throw new InsufficientStockException(
                        "Insufficient stock for {$cartItem->product->name}. " .
                        "Only {$cartItem->product->stock_quantity} items available."
                    );
                }
            }

            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'completed',
                'ordered_at' => now(),
            ]);

            // Create order items and decrease stock
            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);

                // Decrease stock
                $cartItem->product->decreaseStock($cartItem->quantity);

                // Check if stock is low and dispatch notification
                if ($cartItem->product->isLowStock()) {
                    LowStockNotification::dispatch($cartItem->product);
                }
            }

            // Clear cart
            $user->cartItems()->delete();

            return $order->load('orderItems.product');
        });
    }
}
