<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 25px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 28px;
        }
        .header .date {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }
        .stat-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
        }
        .section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .product-name {
            font-weight: 500;
            color: #1f2937;
        }
        .revenue {
            color: #059669;
            font-weight: 600;
        }
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Daily Sales Report</h1>
            <p class="date">{{ \Carbon\Carbon::parse($reportData['date'])->format('l, F j, Y') }}</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value">{{ $reportData['totalOrders'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($reportData['totalRevenue'], 2) }}</div>
            </div>
        </div>

        @if($reportData['bestSellingProducts']->count() > 0)
            <div class="section">
                <h2 class="section-title">🏆 Top Selling Products</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align: center;">Qty Sold</th>
                            <th style="text-align: right;">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['bestSellingProducts'] as $item)
                            <tr>
                                <td class="product-name">{{ $item->product->name }}</td>
                                <td style="text-align: center;">{{ $item->total_quantity }}</td>
                                <td class="revenue" style="text-align: right;">${{ number_format($item->total_revenue, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if($reportData['orders']->count() > 0)
            <div class="section">
                <h2 class="section-title">📦 Orders Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Time</th>
                            <th style="text-align: center;">Items</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['orders'] as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->ordered_at->format('g:i A') }}</td>
                                <td style="text-align: center;">{{ $order->orderItems->sum('quantity') }}</td>
                                <td style="text-align: right;">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="section">
                <div class="empty-state">
                    <p>No orders were placed on this day.</p>
                </div>
            </div>
        @endif

        <div class="footer">
            <p>This is an automated daily sales report from your Simple Cart application.</p>
            <p>Report generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>
