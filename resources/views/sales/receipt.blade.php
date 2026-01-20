<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $sale->sale_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            padding: 50px;
            background: #f8f9fa;
            margin: 0;
        }
        
        .invoice-container {
            max-width: 750px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            z-index: -1;
            opacity: 0.12;
            pointer-events: none;
        }
        
        .watermark-logo {
            width: 350px;
            height: auto;
        }
        
        .watermark-fallback {
            width: 280px;
            height: 280px;
            background: #2d3748;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 15px;
            font-size: 90px;
            font-family: Arial;
        }
        
        .content {
            position: relative;
            z-index: 1;
        }
        
        /* Header Section */
        .header-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .header-logo {
            flex: 0 0 150px;
            text-align: center;
        }
        
        .header-logo img {
            height: 140px;
            width: auto;
        }
        
        .header-logo-fallback {
            width: 140px;
            height: 140px;
            background: #2d3748;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 8px;
            font-size: 32px;
            margin: 0 auto;
        }
        
        .contact-info {
            flex: 1;
            text-align: right;
            padding-left: 25px;
        }
        
        .contact-details {
            font-size: 12px;
            color: #495057;
            line-height: 1.6;
        }
        
        .contact-details div {
            margin-bottom: 4px;
        }
        
        .contact-details div:last-child {
            margin-bottom: 0;
        }
        
        /* Simple line */
        .header-line {
            border: 1px solid #2d3748;
            margin: 5px 0;
        }
        
        /* Bill To and Invoice Info */
        .bill-invoice-section {
            display: flex;
            margin: 20px 0;
        }
        
        .bill-to {
            flex: 1;
            padding-right: 25px;
        }
        
        .bill-to h4 {
            color: #2d3748;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .bill-to-details {
            font-size: 12px;
            color: #4A5568;
            line-height: 1.4;
        }
        
        .bill-to-details div {
            margin-bottom: 5px;
        }
        
        .invoice-info {
            flex: 1;
            text-align: right;
        }
        
        .invoice-details {
            font-size: 12px;
            color: #4A5568;
            line-height: 1.5;
        }
        
        .invoice-details div {
            margin-bottom: 5px;
        }
        
        .payment-badge {
            background: #38A169;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        /* Products Table */
        .products-section {
            margin: 20px 0;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .products-table thead tr {
            background: #2d3748;
        }
        
        .products-table th {
            color: white;
            text-align: center;
            padding: 12px 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
        }
        
        .products-table th:nth-child(1) { width: 8%; }
        .products-table th:nth-child(2) { width: 50%; text-align: left; }
        .products-table th:nth-child(3) { width: 12%; }
        .products-table th:nth-child(4) { width: 15%; }
        .products-table th:nth-child(5) { width: 15%; }
        
        .products-table tbody tr {
            background: white;
            border-bottom: 1px solid #E2E8F0;
        }
        
        .products-table td {
            text-align: center;
            padding: 8px 6px;
            font-size: 11px;
            color: #4A5568;
        }
        
        .products-table td:nth-child(2) {
            text-align: left;
            color: #2D3748;
        }
        
        .product-brand {
            color: #6c757d;
            font-size: 9px;
        }
        
        /* Notes and Totals */
        .notes-totals-section {
            display: flex;
            margin-top: 30px;
        }
        
        .special-notes {
            flex: 1;
            padding-right: 25px;
        }
        
        .special-notes h5 {
            color: #4A90E2;
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .notes-text {
            font-size: 10px;
            line-height: 1.4;
            color: #718096;
            text-align: justify;
        }
        
        .totals-box {
            flex: 1;
            border: 1px solid #dee2e6;
            padding: 18px;
            border-radius: 8px;
        }
        
        .totals-table {
            width: 100%;
            font-size: 12px;
        }
        
        .totals-table td {
            padding: 6px 0;
        }
        
        .totals-table td:first-child {
            color: #495057;
            font-weight: 500;
        }
        
        .totals-table td:last-child {
            text-align: right;
            color: #212529;
            font-weight: 600;
        }
        
        .discount-row td {
            color: #dc3545;
        }
        
        .total-row {
            border-top: 2px solid #2d3748;
        }
        
        .total-row td {
            padding-top: 10px;
            padding-bottom: 6px;
            color: #2d3748;
            font-size: 14px;
            font-weight: 700;
        }
        
        .due-row td {
            color: #fd7e14;
        }
        
        /* Installment Schedule */
        .installments-section {
            margin-top: 30px;
        }
        
        .installments-section h5 {
            color: #2d3748;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .installments-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .installments-table thead tr {
            background: #3498db;
        }
        
        .installments-table th {
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #bdc3c7;
        }
        
        .installments-table tbody tr {
            background: white;
        }
        
        .installments-table td {
            text-align: center;
            padding: 8px;
            font-size: 11px;
            color: #4A5568;
            border: 1px solid #bdc3c7;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #2d3748;
        }
        
        .signature-boxes {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .signature-box {
            flex: 1;
            text-align: center;
        }
        
        .signature-box:first-child {
            margin-right: 50px;
        }
        
        .signature-box:last-child {
            margin-left: 50px;
        }
        
        .signature-line {
            border-bottom: 2px solid #2d3748;
            margin-bottom: 8px;
            padding-bottom: 25px;
            min-height: 50px;
        }
        
        .signature-label {
            font-size: 12px;
            color: #495057;
            margin: 0;
            font-weight: 600;
        }
        
        .signature-name {
            font-size: 10px;
            color: #6c757d;
            margin: 0;
        }
        
        /* Support Info */
        .support-info {
            display: flex;
            justify-content: space-between;
            margin: 25px 0 20px 0;
            font-size: 11px;
            color: #495057;
        }
        
        .support-column {
            flex: 1;
        }
        
        .support-column:first-child {
            padding-right: 15px;
        }
        
        .support-column:last-child {
            padding-left: 15px;
        }
        
        .support-column h4 {
            color: #2d3748;
            font-size: 12px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .support-column p {
            margin: 0;
            line-height: 1.4;
        }
        
        /* Why Choose Section */
        .why-choose {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        
        .why-choose-title {
            color: #2d3748;
            font-size: 11px;
            margin: 0 0 8px 0;
            font-weight: 600;
        }
        
        .why-choose-features {
            display: flex;
            justify-content: space-around;
            font-size: 10px;
            color: #495057;
        }
        
        .closing-text {
            text-align: center;
            margin-top: 15px;
            color: #6c757d;
            font-size: 10px;
            margin-bottom: 0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        
        <!-- Watermark -->
        <div class="watermark">
            <div class="watermark-fallback">
                <div style="font-size: 72px; font-weight: bold; color: #2d3748; opacity: 0.1; font-family: 'Arial Black', Arial, sans-serif; text-align: center; line-height: 1;">MARIYA<br>ELECTRONICS</div>
            </div>
        </div>
        
        <div class="content">
        
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-logo">
                <div class="header-logo-fallback" style="background: #2d3748; color: white; border-radius: 8px; padding: 20px; text-align: center; line-height: 1.2;">
                    <div style="font-size: 24px; font-weight: bold; margin-bottom: 5px;">MARIYA</div>
                    <div style="font-size: 16px; font-weight: 600;">ELECTRONICS</div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-info">
                <div class="contact-details">
                    <div><strong>Address:</strong> Walton Mor, Bashergoli, Demra, Dhaka</div>
                    <div><strong>Mobile:</strong> +123-456-7890</div>
                    <div><strong>Mobile:</strong> +123-456-7890</div>
                    <div><strong>Mobile:</strong> +123-456-7890</div>
                </div>
            </div>
        </div>
        
        <!-- Simple Line -->
        <hr class="header-line">

        <!-- Bill To and Invoice Info -->
        <div class="bill-invoice-section">
            <div class="bill-to">
                <h4>Bill To:</h4>
                <div class="bill-to-details">
                    <div><strong>Name:</strong> {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Mahidul Ratul') }}</div>
                    <div><strong>Address:</strong> {{ $sale->customer ? $sale->customer->address : ($sale->customer_address ?? 'hhuh gyhj') }}</div>
                    <div><strong>Mobile No.:</strong> {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? '01797778412') }}</div>
                </div>
            </div>
            
            <div class="invoice-info">
                <div class="invoice-details">
                    <div><strong>INVOICE NO:</strong> {{ $sale->sale_number }}</div>
                    <div><strong>DATE:</strong> {{ $sale->sale_date->format('d/m/Y') }}</div>
                    <div><strong>PAYMENT TYPE:</strong> <span class="payment-badge">{{ strtoupper($sale->payment_type) }}</span></div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="products-section">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Description</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>
                            @if($sale->product)
                                {{ $sale->product->name }}
                                @if($sale->product->brand)
                                    <br><small class="product-brand">Brand: {{ $sale->product->brand }}</small>
                                @endif
                                @if($sale->product->model)
                                    <br><small class="product-brand">Model: {{ $sale->product->model }}</small>
                                @endif
                            @elseif($sale->products_data && isset($sale->products_data['name']))
                                {{ $sale->products_data['name'] }}
                                @if(isset($sale->products_data['brand']) && $sale->products_data['brand'])
                                    <br><small class="product-brand">Brand: {{ $sale->products_data['brand'] }}</small>
                                @endif
                                @if(isset($sale->products_data['model']) && $sale->products_data['model'])
                                    <br><small class="product-brand">Model: {{ $sale->products_data['model'] }}</small>
                                @endif
                            @else
                                Washing Machine
                                <br><small class="product-brand">Brand: Walton</small>
                                <br><small class="product-brand">Model: WM120</small>
                            @endif
                        </td>
                        <td>{{ $sale->quantity ?? 1 }}</td>
                        <td>৳ {{ number_format((float)$sale->unit_price, 2) }}</td>
                        <td>৳ {{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($sale->payment_type === 'installment' && $sale->installments->count() > 0)
        <!-- Installment Schedule -->
        <div class="installments-section">
            <h5>Installment Schedule</h5>
            <table class="installments-table">
                <thead>
                    <tr>
                        <th>Installment #</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->installments as $index => $installment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $installment->due_date->format('d/m/Y') }}</td>
                        <td>৳ {{ number_format((float)$installment->amount, 2) }}</td>
                        <td>
                            @if($installment->status === 'paid')
                                <span style="color: #28a745;">Paid</span>
                            @elseif($installment->status === 'overdue')
                                <span style="color: #dc3545;">Overdue</span>
                            @else
                                <span style="color: #ffc107;">Pending</span>
                            @endif
                        </td>
                        <td>{{ $installment->paid_date ? $installment->paid_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Notes and Totals -->
        <div class="notes-totals-section">
            <div class="special-notes">
                <h5>Special Notes:</h5>
                <p class="notes-text">
                    {{ $sale->notes ?? 'hkl;;hgg' }}
                </p>
            </div>
            
            <div class="totals-box">
                <table class="totals-table">
                    <tr>
                        <td>Sub Total:</td>
                        <td>৳ {{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)($sale->quantity ?? 1)), 2) }}</td>
                    </tr>
                    @if($sale->discount_amount > 0)
                    <tr class="discount-row">
                        <td>Discount:</td>
                        <td>-৳ {{ number_format((float)$sale->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total Amount:</td>
                        <td>৳ {{ number_format((float)$sale->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Paid Amount:</td>
                        <td>৳ {{ number_format((float)$sale->paid_amount, 2) }}</td>
                    </tr>
                    @if((float)$sale->total_amount - (float)$sale->paid_amount > 0)
                    <tr class="due-row">
                        <td>Due Amount:</td>
                        <td>৳ {{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-boxes">
                <!-- Customer Signature -->
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p class="signature-label">Customer Signature</p>
                    <p class="signature-name">{{ $sale->customer->name ?? 'Customer' }}</p>
                </div>
                
                <!-- Seller Signature -->
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p class="signature-label">Authorized Signature</p>
                    <p class="signature-name">Mariya Electronics</p>
                </div>
            </div>
            
            <!-- Warranty & Support Information -->
            <div class="support-info">
                <div class="support-column">
                    <h4>📞 Customer Support</h4>
                    <p>For any queries or support, please contact us at our mobile numbers above. Our team is ready to assist you.</p>
                </div>
                <div class="support-column">
                    <h4>🔧 Warranty & Service</h4>
                    <p>All products come with manufacturer warranty. Keep this invoice for warranty claims and future reference.</p>
                </div>
            </div>
            
            <div class="why-choose">
                <p class="why-choose-title">💡 Why Choose Mariya Electronics?</p>
                <div class="why-choose-features">
                    <span>✓ Quality Products</span>
                    <span>✓ Best Prices</span>
                    <span>✓ Expert Service</span>
                    <span>✓ Quick Delivery</span>
                </div>
            </div>
            
            <p class="closing-text">Visit us again for all your electronics and appliances needs • Trusted since establishment</p>
        </div>
        </div>
    </div>
</body>
</html>