<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Accepted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            background-color: #fcd34d;
            padding: 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #000;
        }
        .content {
            padding: 20px;
        }
        .items {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .items th, .items td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .footer {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        Your Guitar Order Has Been Accepted 🎸
    </div>

    <div class="content">
        <p>Hello {{ $order->user->name ?? 'Customer' }},</p>

        <p>Thank you for shopping with us! Your order has been accepted and is now being prepared for shipment.</p>

        <p><strong>Delivery Address:</strong><br>
        {{ $order->address }}</p>

        <h4>Order Details:</h4>
        <table class="items">
            <thead>
                <tr>
                    <th>Guitar</th>
                    <th>Quantity</th>
                    <th>Price (₱)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->guitar->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
        
        @php
            $deliveryEstimate = 'For local: 2-3 business days, For international: 4-7 business days';
        @endphp

        <p><strong>Estimated Delivery Time:</strong> {{ $deliveryEstimate }}</p>

        <div class="footer">
            <p>If you have any questions, feel free to reply to this email.</p>
            <p>Happy strumming! 🎶</p>
        </div>
    </div>
</body>
</html>
