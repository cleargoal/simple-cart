<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(): Response
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Display the specified product
     */
    public function show(Request $request, Product $product): Response
    {
        // Track recently viewed products in session
        $recentlyViewed = $request->session()->get('recently_viewed', []);

        // Remove if already exists to avoid duplicates
        $recentlyViewed = array_diff($recentlyViewed, [$product->id]);

        // Add to the beginning of the array
        array_unshift($recentlyViewed, $product->id);

        // Keep only last 20 viewed products
        $recentlyViewed = array_slice($recentlyViewed, 0, 20);

        $request->session()->put('recently_viewed', $recentlyViewed);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
