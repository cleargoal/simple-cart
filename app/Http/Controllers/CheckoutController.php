<?php

namespace App\Http\Controllers;

use App\Actions\Order\ProcessCheckout;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    /**
     * Process checkout and create order
     */
    public function store(ProcessCheckout $action): RedirectResponse
    {
        try {
            $order = $action->execute(auth()->user());

            return redirect()
                ->route('products.index')
                ->with('success', "Order #{$order->id} placed successfully! Total: \${$order->total}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
