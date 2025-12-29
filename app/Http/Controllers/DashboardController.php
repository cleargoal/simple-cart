<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Quick Stats
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()
            ->where('status', '!=', 'cancelled')
            ->sum('total') / 100; // Convert from cents to dollars
        $cartValue = $user->cartItems()
            ->with('product')
            ->get()
            ->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->quantity;
            });
        $wishlistCount = $user->wishlists()->count();

        // Recent Orders (last 5)
        $recentOrders = $user->orders()
            ->with('orderItems.product')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'created_at' => $order->created_at->format('M d, Y'),
                    'status' => $order->status,
                    'total_amount' => $order->total, // Money cast will handle conversion
                    'items_count' => $order->orderItems->count(),
                    'products' => $order->orderItems->take(3)->map(fn($item) => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'image_url' => $item->product->image_url,
                    ]),
                ];
            });

        // Recently Viewed Products (from session)
        $recentlyViewedIds = $request->session()->get('recently_viewed', []);
        $recentlyViewed = Product::whereIn('id', array_slice($recentlyViewedIds, 0, 6))
            ->where('stock_quantity', '>', 0)
            ->get();

        // Recommended Products (based on purchase history)
        $purchasedCategoryIds = $user->orders()
            ->with('orderItems.product')
            ->get()
            ->pluck('orderItems')
            ->flatten()
            ->pluck('product')
            ->pluck('id')
            ->unique();

        $recommendedProducts = Product::whereNotIn('id', $purchasedCategoryIds)
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Wishlist Items
        $wishlistItems = $user->wishlists()
            ->with('product')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn($wishlist) => $wishlist->product);

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalOrders' => $totalOrders,
                'totalSpent' => $totalSpent,
                'cartValue' => $cartValue,
                'wishlistCount' => $wishlistCount,
            ],
            'recentOrders' => $recentOrders,
            'recentlyViewed' => $recentlyViewed,
            'recommendedProducts' => $recommendedProducts,
            'wishlistItems' => $wishlistItems,
        ]);
    }
}
