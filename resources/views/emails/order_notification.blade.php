<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন অর্ডার</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
        }
        .order-info {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            margin: 15px 0;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .product-table th {
            background: #4CAF50;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .product-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .total-section {
            background: #f9f9f9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛍️ নতুন অর্ডার পাওয়া গেছে!</h1>
        </div>
        
        <div class="content">
            <div class="order-info">
                <h2>অর্ডার তথ্য</h2>
                <p><strong>Invoice ID:</strong> {{ $orderData['invoice_id'] }}</p>
                <p><strong>তারিখ:</strong> {{ $orderData['order_date'] }}</p>
            </div>

            <div class="order-info">
                <h2>কাস্টমার তথ্য</h2>
                <p><strong>নাম:</strong> {{ $orderData['customer_name'] }}</p>
                <p><strong>ফোন:</strong> {{ $orderData['customer_phone'] }}</p>
                <p><strong>ঠিকানা:</strong> {{ $orderData['customer_address'] }}</p>
            </div>

            <h2>প্রোডাক্ট বিবরণ</h2>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>প্রোডাক্ট</th>
                        <th>পরিমাণ</th>
                        <th>মূল্য</th>
                        <th>মোট</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderData['products'] as $product)
                    <tr>
                        <td>
                            {{ $product['name'] }}
                            @if($product['color'])
                                <br><small>Color: {{ $product['color'] }}</small>
                            @endif
                            @if($product['size'])
                                <br><small>Size: {{ $product['size'] }}</small>
                            @endif
                        </td>
                        <td>{{ $product['qty'] }}</td>
                        <td>৳{{ number_format($product['price'], 2) }}</td>
                        <td>৳{{ number_format($product['price'] * $product['qty'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <p><strong>ডিসকাউন্ট:</strong> ৳{{ number_format($orderData['discount'], 2) }}</p>
                <p><strong>ডেলিভারি চার্জ:</strong> ৳{{ number_format($orderData['shipping_charge'], 2) }}</p>
                <h3><strong>সর্বমোট:</strong> ৳{{ number_format($orderData['amount'], 2) }}</h3>
            </div>

            @if($orderData['note'])
            <div class="order-info">
                <h2>বিশেষ নোট</h2>
                <p>{{ $orderData['note'] }}</p>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>এই অর্ডারটি আপনার সিস্টেম থেকে স্বয়ংক্রিয়ভাবে তৈরি হয়েছে।</p>
        </div>
    </div>
</body>
</html>