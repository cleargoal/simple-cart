<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
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
            background-color: #eaa30c;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .product-info {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        .product-name {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .stock-info {
            font-size: 18px;
            color: #eaa30c;
            font-weight: 600;
            margin: 15px 0;
        }
        .details {
            color: #6b7280;
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Low Stock Alert</h1>
        </div>

        <p>This is an automated notification to inform you that one of your products is running low on stock.</p>

        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>

            @if($product->description)
                <p class="details">{{ $product->description }}</p>
            @endif

            <div class="stock-info">
                Current Stock: {{ $product->stock_quantity }} units
            </div>

            <div class="details">
                <strong>Product ID:</strong> {{ $product->id }}<br>
                <strong>Price:</strong> ${{ number_format($product->price, 2) }}
            </div>
        </div>

        <p>Please consider restocking this product to avoid running out of inventory.</p>

        <div class="footer">
            <p>This is an automated message from your Simple Cart application. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
