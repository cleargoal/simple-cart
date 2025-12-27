<?php

namespace App\Http\Controllers;

use App\Actions\Cart\AddToCart;
use App\Actions\Cart\RemoveFromCart;
use App\Actions\Cart\UpdateCartItem;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function index(): Response
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with('product')
            ->get();

        $total = $cartItems->sum('subtotal');

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Add product to cart
     */
    public function store(AddToCartRequest $request, AddToCart $action): RedirectResponse
    {
        try {
            $action->execute(
                auth()->user(),
                $request->validated('product_id'),
                $request->validated('quantity')
            );

            return back()->with('success', 'Product added to cart');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(
        UpdateCartItemRequest $request,
        CartItem $cartItem,
        UpdateCartItem $action
    ): RedirectResponse {
        try {
            $action->execute(
                auth()->user(),
                $cartItem->id,
                $request->validated('quantity')
            );

            return back()->with('success', 'Cart updated');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(CartItem $cartItem, RemoveFromCart $action): RedirectResponse
    {
        try {
            $action->execute(auth()->user(), $cartItem->id);

            return back()->with('success', 'Item removed from cart');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
