<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->sale_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .logo-section {
            display: table;
            width: 100%;
        }

        .logo-left, .logo-right {
            display: table-cell;
            vertical-align: top;
        }

        .logo-left {
            width: 60%;
        }

        .logo-right {
            width: 40%;
            text-align: right;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            float: left;
            margin-right: 15px;
            border-radius: 8px;
            display: block;
        }

        .company-info {
            margin-left: 0;
        }

        .company-info.with-logo {
            margin-left: 95px;
        }

        .company-info h1 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .company-info p {
            margin-bottom: 2px;
            color: #666;
        }

        .invoice-info h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .invoice-details {
            margin-bottom: 5px;
        }

        .badge {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .customer-section {
            margin-bottom: 30px;
        }

        .customer-box {
            display: table;
            width: 100%;
        }

        .bill-to, .shop-details {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }

        .bill-to {
            margin-right: 4%;
        }

        .customer-box h3 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .totals-section {
            width: 250px;
            margin-left: auto;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td,
        .totals-table th {
            padding: 5px 8px;
            border: none;
        }

        .totals-table .border-top {
            border-top: 1px solid #333;
        }

        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #ffc107;
        }

        .notes-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .notes-section h4 {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .footer-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .terms, .signature {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .signature {
            text-align: right;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin-left: auto;
            margin-top: 30px;
            padding-top: 5px;
        }

        .terms h4 {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .terms ul {
            margin-left: 15px;
        }

        .terms li {
            margin-bottom: 2px;
            font-size: 10px;
            color: #666;
        }

        .thank-you {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 10px;
        }

        .thank-you p {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo-left">
                    @if(extension_loaded('gd'))
                    <img src="{{ public_path('images/me_logo2.png') }}" alt="Mariya Electronics Logo" class="company-logo">
                    <div class="company-info with-logo">
                    @else
                    <div class="company-info">
                    @endif
                        <h1>MARIYA ELECTRONICS</h1>
                        <p>Electronics & Appliances Store</p>
                        <p>Phone: +880-XXX-XXXXXXX</p>
                        <p>Email: info@mariyaelectronics.com</p>
                    </div>
                </div>
                <div class="logo-right">
                    <h2>INVOICE</h2>
                    <div class="invoice-details">
                        <strong>Invoice No:</strong> {{ $sale->sale_number }}
                    </div>
                    <div class="invoice-details">
                        <strong>Date:</strong> {{ $sale->sale_date->format('M d, Y') }}
                    </div>
                    <div class="invoice-details">
                        <strong>Payment Type:</strong> 
                        <span class="badge">{{ ucfirst($sale->payment_type) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="customer-section">
            <div class="customer-box">
                <div class="bill-to">
                    <h3>Bill To:</h3>
                    <div style="margin-bottom: 5px;">
                        <strong>Name:</strong> {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Walk-in Customer') }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong>Phone:</strong> {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? 'N/A') }}
                    </div>
                    @if($sale->customer && $sale->customer->address || $sale->customer_address)
                    <div style="margin-bottom: 5px;">
                        <strong>Address:</strong> {{ $sale->customer ? $sale->customer->address : $sale->customer_address }}
                    </div>
                    @endif
                </div>
                <div class="shop-details">
                    <h3>Shop Details:</h3>
                    <div style="margin-bottom: 5px;">
                        <strong>Mariya Electronics</strong>
                    </div>
                    <div style="margin-bottom: 5px;">
                        Address: Your Shop Address Here
                    </div>
                    <div style="margin-bottom: 5px;">
                        Phone: +880-XXX-XXXXXXX
                    </div>
                    <div style="margin-bottom: 5px;">
                        Email: info@mariyaelectronics.com
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">#</th>
                    <th style="width: 50%;">Product Description</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-end" style="width: 15%;">Unit Price</th>
                    <th class="text-end" style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @if($sale->products_data && is_array($sale->products_data) && count($sale->products_data) > 0)
                    @foreach($sale->products_data as $product)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $product['name'] ?? 'Product N/A' }}</strong>
                            @if(isset($product['brand']) && $product['brand'])
                                <br><small style="color: #666;">Brand: {{ $product['brand'] }}</small>
                            @endif
                            @if(isset($product['model']) && $product['model'])
                                <br><small style="color: #666;">Model: {{ $product['model'] }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $sale->quantity ?? 1 }}</td>
                        <td class="text-end">৳{{ number_format((float)$sale->unit_price, 2) }}</td>
                        <td class="text-end">৳{{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            <strong>{{ $sale->product ? $sale->product->name : 'Product N/A' }}</strong>
                            @if($sale->product && $sale->product->brand)
                                <br><small style="color: #666;">Brand: {{ $sale->product->brand }}</small>
                            @endif
                            @if($sale->product && $sale->product->model)
                                <br><small style="color: #666;">Model: {{ $sale->product->model }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $sale->quantity }}</td>
                        <td class="text-end">৳{{ number_format((float)$sale->unit_price, 2) }}</td>
                        <td class="text-end">৳{{ number_format((float)$sale->unit_price * (int)$sale->quantity, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <th>Subtotal:</th>
                    <td class="text-end">৳{{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)$sale->quantity), 2) }}</td>
                </tr>
                @if($sale->discount_amount > 0)
                <tr>
                    <th>Discount:</th>
                    <td class="text-end text-danger">-৳{{ number_format((float)$sale->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="border-top">
                    <th class="total-amount">Total Amount:</th>
                    <td class="text-end total-amount">৳{{ number_format((float)$sale->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Paid Amount:</th>
                    <td class="text-end">৳{{ number_format((float)$sale->paid_amount, 2) }}</td>
                </tr>
                @if((float)$sale->total_amount - (float)$sale->paid_amount > 0)
                <tr>
                    <th>Due Amount:</th>
                    <td class="text-end text-warning" style="font-weight: bold;">৳{{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>

        @if($sale->notes)
        <!-- Notes -->
        <div class="notes-section">
            <h4>Notes:</h4>
            <p style="color: #666;">{{ $sale->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer-section">
            <div class="footer-content">
                <div class="terms">
                    <h4>Terms & Conditions:</h4>
                    <ul>
                        <li>All sales are final</li>
                        <li>Warranty as per manufacturer's policy</li>
                        <li>For any queries, contact us at the above number</li>
                    </ul>
                </div>
                <div class="signature">
                    <div class="signature-line">
                        <p style="text-align: center; margin-top: 5px;"><strong>Authorized Signature</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="thank-you">
            <p><strong>Thank you for your business!</strong></p>
            <p>Visit us again for quality electronics and appliances</p>
        </div>
    </div>
</body>
</html>