<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->sale_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .invoice-details > div {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-details .right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        .totals table {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">MARIYA ELECTRONICS</div>
        <p>Electronics & Mobile Shop</p>
    </div>

    <div class="invoice-details">
        <div>
            <strong>Customer Details:</strong><br>
            {{ $sale->customer->name ?? 'Walk-in Customer' }}<br>
            {{ $sale->customer->phone ?? '' }}<br>
            {{ $sale->customer->address ?? '' }}
        </div>
        <div class="right">
            <strong>Invoice: {{ $sale->sale_number }}</strong><br>
            Date: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-M-Y') }}<br>
            Sale Type: {{ ucfirst($sale->sale_type) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $sale->product->name ?? 'Product' }}</td>
                <td>{{ $sale->quantity ?? 1 }}</td>
                <td>BDT {{ number_format((float)$sale->unit_price, 2) }}</td>
                <td>BDT {{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>BDT {{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)($sale->quantity ?? 1)), 2) }}</td>
            </tr>
            @if($sale->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td>-BDT {{ number_format((float)$sale->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td>BDT {{ number_format((float)$sale->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Paid:</td>
                <td>BDT {{ number_format((float)$sale->paid_amount, 2) }}</td>
            </tr>
            @if($sale->total_amount - $sale->paid_amount > 0)
            <tr>
                <td>Due:</td>
                <td>BDT {{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div style="clear: both; margin-top: 50px; text-align: center; font-size: 10px;">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>