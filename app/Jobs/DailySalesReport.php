<?php

namespace App\Jobs;

use App\Mail\DailySalesReport as DailySalesReportMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DailySalesReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get yesterday's date range
        $startDate = now()->subDay()->startOfDay();
        $endDate = now()->subDay()->endOfDay();

        // Get orders from yesterday
        $orders = Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->with('orderItems.product')
            ->get();

        // Calculate statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');

        // Get best-selling products from yesterday
        $bestSellingProducts = OrderItem::query()
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('ordered_at', [$startDate, $endDate]);
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $reportData = [
            'date' => $startDate->toDateString(),
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'bestSellingProducts' => $bestSellingProducts,
            'orders' => $orders,
        ];

        // Send to all configured admin emails
        $adminEmails = config('mail.admin_emails');

        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new DailySalesReportMail($reportData));
        }
    }
}
