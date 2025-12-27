<?php

namespace App\Actions\Cart;

use App\Models\CartItem;
use App\Models\User;

class RemoveFromCart
{
    /**
     * Remove item from cart
     */
    public function execute(User $user, int $cartItemId): bool
    {
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        return $cartItem->delete();
    }
}
