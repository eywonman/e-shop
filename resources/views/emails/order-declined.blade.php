<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Declined</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            background-color: #f87171;
            padding: 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: white;
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
        Your Order Has Been Declined ❌
    </div>

    <div class="content">
        <p>Hello {{ $order->user->name ?? 'Customer' }},</p>

        <p>We regret to inform you that your recent guitar order has been declined. Please review the details below:</p>

        <div class="details">
            <p><strong>Order #:</strong> {{ $order->order_number ?? 'ORD-N/A' }}</p>
            <p><strong>Address Provided:</strong><br>
            {{ $order->address }}</p>
        </div>

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

        <p><strong>Total Attempted Order Amount:</strong> ₱{{ number_format($order->total_price, 2) }}</p>

        <div class="footer">
            <p>If this was a mistake or you would like to reorder, feel free to visit our website and try again.</p>
            <p>We appreciate your interest in our guitars. 🎸</p>
            <p>– The A&A Guitar Shop Team</p>
        </div>
    </div>
</body>
</html>
