<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Installment Invoice - {{ $installmentSale->installment_sale_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 11px;
            color: #333;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 500px;
            height: 500px;
            margin-left: -250px;
            margin-top: -250px;
            opacity: 0.20;
            z-index: -1;
        }
        .header-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .logo-cell {
            width: 140px;
            vertical-align: middle;
            text-align: center;
        }
        .contact-cell {
            vertical-align: middle;
            text-align: right;
            font-size: 11px;
            color: #495057;
            padding-left: 20px;
        }
        .divider {
            border-top: 2px solid #2d3748;
            margin: 10px 0;
        }
        .info-table {
            width: 100%;
            margin: 15px 0;
        }
        .bill-to-cell {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .invoice-info-cell {
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .section-title {
            color: #2d3748;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .payment-badge {
            background: #38A169;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .products-table th {
            background: #2d3748;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #2d3748;
        }
        .products-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
        }
        .products-table td.left {
            text-align: left;
        }
        .notes-totals-table {
            width: 100%;
            margin-top: 20px;
            table-layout: fixed;
        }
        .notes-cell {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .notes-cell p {
            max-width: 100%;
            word-break: break-word;
        }
        .totals-cell {
            width: 50%;
            vertical-align: top;
        }
        .notes-title {
            color: #4A90E2;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .totals-box {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
        }
        .totals-inner-table {
            width: 100%;
            font-size: 10px;
        }
        .totals-inner-table td {
            padding: 5px 3px;
        }
        .totals-inner-table .total-row {
            font-weight: bold;
            font-size: 12px;
            color: #2d3748;
        }
        .totals-inner-table .total-row td {
            padding: 7px 3px;
        }
        .totals-inner-table .installment-row {
            color: #17a2b8;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 30px;
            padding-top: 20px;
        }
        .signature-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .signature-cell {
            width: 45%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            border-bottom: 2px solid #2d3748;
            height: 50px;
            margin-bottom: 8px;
        }
        .support-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .support-cell {
            width: 50%;
            vertical-align: top;
            font-size: 10px;
            padding: 0 10px;
        }
        .support-title {
            color: #2d3748;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .why-choose {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: center;
            margin-bottom: 10px;
        }
        .features-table {
            width: 100%;
        }
        .features-table td {
            text-align: center;
            font-size: 9px;
            padding: 5px;
        }
        .footer-text {
            text-align: center;
            font-size: 9px;
            color: #6c757d;
            font-style: italic;
        }
        .page-break {
            page-break-before: always;
        }
        /* Schedule page styles */
        .schedule-header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 8px;
        }
        .schedule-header h2 {
            color: #2d3748;
            font-size: 16px;
            margin: 0 0 3px 0;
        }
        .schedule-summary {
            width: 100%;
            margin-bottom: 10px;
            background: #f8f9fa;
            padding: 8px;
        }
        .schedule-summary td {
            font-size: 10px;
            padding: 2px;
            line-height: 1.4;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .schedule-table th {
            background: #2d3748;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #2d3748;
            font-size: 10px;
        }
        .schedule-table td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
        }
        .status-badge {
            background: #ffc107;
            color: #333;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .terms-box {
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            padding: 8px;
            margin-bottom: 10px;
        }
        .terms-box h4 {
            color: #856404;
            font-size: 10px;
            margin: 0 0 5px 0;
        }
        .terms-box ul {
            margin: 0;
            padding-left: 18px;
            font-size: 8px;
            line-height: 1.4;
            color: #856404;
        }
        .signature-line-border {
            border-bottom: 1px solid #2d3748;
            height: 25px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" style="width: 500px; height: 500px;" alt="ME Watermark">
        @else
            <div style="width: 500px; height: 500px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 24px; border: 2px dashed #ddd;">
                LOGO
            </div>
        @endif
    </div>

    <!-- PAGE 1: Invoice -->
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height: 120px; width: auto;" alt="ME Logo">
                @else
                    <div style="width: 120px; height: 120px; background: #2d3748; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 8px; margin: 0 auto;">
                        <div style="text-align: center; line-height: 1.2;">
                            <div style="font-size: 18px;">ME</div>
                            <div style="font-size: 10px;">LOGO</div>
                        </div>
                    </div>
                @endif
            </td>
            <td class="contact-cell">
                <strong>Mobile:</strong> 01711392676<br>
                <strong>Mobile:</strong> 01977183874<br>
                <strong>Address:</strong> Walton mor, Basherpul road, Demra, Dhaka<br>
                <strong>Email:</strong> mariyaelectronics@gmail.com
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Bill To and Invoice Info -->
    <table class="info-table">
        <tr>
            <td class="bill-to-cell">
                <div class="section-title">Bill To:</div>
                <strong>Name:</strong> {{ $installmentSale->customer_name }}<br>
                <strong>Address:</strong> {{ $installmentSale->customer_address }}<br>
                <strong>Mobile No.:</strong> {{ $installmentSale->customer_mobile }}
            </td>
            <td class="invoice-info-cell">
                <strong>INVOICE NO:</strong> {{ $installmentSale->installment_sale_number }}<br>
                <strong>DATE:</strong> {{ $installmentSale->sale_date instanceof \Carbon\Carbon ? $installmentSale->sale_date->format('d/m/Y') : (\Carbon\Carbon::parse($installmentSale->sale_date)->format('d/m/Y')) }}<br>
                <strong>PAYMENT TYPE:</strong> <span class="payment-badge">INSTALLMENT</span>
            </td>
        </tr>
    </table>

    <!-- Products Table -->
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 50%;">Product Description</th>
                <th style="width: 12%;">Qty</th>
                <th style="width: 15%;">Unit Price</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $products = [];
                if (is_string($installmentSale->products_data)) {
                    $products = json_decode($installmentSale->products_data, true) ?? [];
                } elseif (is_array($installmentSale->products_data)) {
                    $products = $installmentSale->products_data;
                }
            @endphp
            
            @foreach($products as $index => $product)
            <tr>
                <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="left">
                    {{ $product['product_name'] ?? 'Product' }}
                    @if(isset($product['brand']) && $product['brand'])
                        <br><small style="color: #6c757d;">Brand: {{ $product['brand'] }}</small>
                    @endif
                    @if(isset($product['model']) && $product['model'])
                        <br><small style="color: #6c757d;">Model: {{ $product['model'] }}</small>
                    @endif
                </td>
                <td>{{ $product['quantity'] ?? 1 }}</td>
                <td>BDT {{ number_format((float)($product['unit_price'] ?? 0), 2) }}</td>
                <td>BDT {{ number_format((float)($product['unit_price'] ?? 0) * (int)($product['quantity'] ?? 1), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Notes and Totals -->
    <table class="notes-totals-table">
        <tr>
            <td class="notes-cell">
                <div class="notes-title">Special Notes:</div>
                <p style="font-size: 9px; line-height: 1.4; color: #718096; text-align: justify;">
                    {{ $installmentSale->notes ?? 'N/A' }}
                </p>
            </td>
            <td class="totals-cell">
                <div class="totals-box">
                    <table class="totals-inner-table">
                        <tr>
                            <td>Sub Total:</td>
                            <td style="text-align: right;">BDT {{ number_format($installmentSale->subtotal, 2) }}</td>
                        </tr>
                        @if($installmentSale->discount_amount > 0)
                        <tr>
                            <td>Discount:</td>
                            <td style="text-align: right;">BDT -{{ number_format((float)$installmentSale->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td>Total Amount:</td>
                            <td style="text-align: right;">BDT {{ number_format((float)$installmentSale->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Down Payment:</td>
                            <td style="text-align: right;">BDT {{ number_format((float)$installmentSale->down_payment, 2) }}</td>
                        </tr>
                        <tr style="color: #fd7e14; font-weight: 600;">
                            <td>Remaining Amount:</td>
                            <td style="text-align: right;">BDT {{ number_format((float)$installmentSale->total_amount - (float)$installmentSale->down_payment, 2) }}</td>
                        </tr>
                        <tr class="installment-row">
                            <td>Monthly Installment:</td>
                            <td style="text-align: right;">BDT {{ number_format((float)$installmentSale->monthly_installment, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 9px; color: #6c757d;">Total Months:</td>
                            <td style="text-align: right; font-size: 9px; color: #6c757d;">{{ $installmentSale->total_installments }} months</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <div class="signature-line"></div>
                    <strong>Customer Signature</strong><br>
                    <small style="color: #6c757d;">{{ $installmentSale->customer_name }}</small>
                </td>
                <td style="width: 10%;"></td>
                <td class="signature-cell">
                    <div class="signature-line"></div>
                    <strong>Authorized Signature</strong><br>
                    <small style="color: #6c757d;">Mariya Electronics</small>
                </td>
            </tr>
        </table>

        <!-- Support Information -->
        <table class="support-table">
            <tr>
                <td class="support-cell">
                    <div class="support-title">Customer Support</div>
                    For any queries or support, please contact us at our mobile numbers above. Our team is ready to assist you.
                </td>
                <td class="support-cell">
                    <div class="support-title">Installment Terms</div>
                    Please ensure timely payment of monthly installments. Keep this invoice for payment records and reference.
                </td>
            </tr>
        </table>

        <!-- Why Choose -->
        <div class="why-choose">
            <strong style="color: #2d3748; font-size: 11px;">Why Choose Mariya Electronics?</strong><br>
            <table class="features-table">
                <tr>
                    <td>✓ Quality Products</td>
                    <td>✓ Best Prices</td>
                    <td>✓ Expert Service</td>
                    <td>✓ Quick Delivery</td>
                </tr>
            </table>
        </div>

        <div class="footer-text">
            Visit us again for all your electronics and appliances needs • Trusted since establishment
        </div>
    </div>

    <!-- PAGE 2: Installment Schedule -->
    <div class="page-break"></div>
    
    <div class="watermark">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" style="width: 500px; height: 500px;" alt="ME Watermark">
        @else
            <div style="width: 500px; height: 500px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 24px; border: 2px dashed #ddd;">
                LOGO
            </div>
        @endif
    </div>

    <div class="schedule-header">
        <h2>INSTALLMENT PAYMENT SCHEDULE</h2>
        <p style="font-size: 9px; color: #6c757d; margin: 0;">Invoice: {{ $installmentSale->installment_sale_number }}</p>
    </div>

    <!-- Customer Info Summary -->
    <table class="schedule-summary">
        <tr>
            <td style="width: 50%;">
                <strong style="color: #2d3748;">Customer:</strong> {{ $installmentSale->customer_name }}<br>
                <strong style="color: #2d3748;">Mobile:</strong> {{ $installmentSale->customer_mobile }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong style="color: #2d3748;">Total Amount:</strong> BDT {{ number_format($installmentSale->total_amount, 2) }}<br>
                <strong style="color: #2d3748;">Down Payment:</strong> BDT {{ number_format($installmentSale->down_payment, 2) }}<br>
                <strong style="color: #17a2b8;">Monthly:</strong> BDT {{ number_format($installmentSale->monthly_installment, 2) }}
            </td>
        </tr>
    </table>

    <!-- Installment Schedule Table -->
    <table class="schedule-table">
        <thead>
            <tr>
                <th style="width: 15%;">Installment #</th>
                <th style="width: 25%;">Due Date</th>
                <th style="width: 20%;">Amount</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 25%;">Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($installmentSchedule as $installment)
            <tr>
                <td style="font-weight: 600; color: #2d3748;">{{ $installment['number'] }}</td>
                <td>{{ $installment['due_date'] instanceof \Carbon\Carbon ? $installment['due_date']->format('d M Y') : \Carbon\Carbon::parse($installment['due_date'])->format('d M Y') }}</td>
                <td style="font-weight: 600; color: #2d3748;">BDT {{ number_format($installment['amount'], 2) }}</td>
                <td>
                    <span class="status-badge">{{ ucfirst($installment['status']) }}</span>
                </td>
                <td>
                    <div class="signature-line-border"></div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Terms & Conditions -->
    <div class="terms-box">
        <h4>⚠️ Important Terms & Conditions:</h4>
        <ul>
            <li>All installments must be paid on or before the due date mentioned above.</li>
            <li>Late payment charges may apply for overdue payments.</li>
            <li>The customer and guarantor are jointly responsible for all payments.</li>
            <li>Products remain the property of Mariya Electronics until full payment is received.</li>
            <li>Any default in payment may result in repossession of the product.</li>
            <li>This payment schedule is subject to the terms agreed upon at the time of sale.</li>
        </ul>
    </div>

    <!-- Signatures for Schedule Page -->
    <table class="signature-table" style="margin-top: 15px; border-top: 2px solid #2d3748; padding-top: 10px;">
        <tr>
            <td class="signature-cell" style="width: 30%;">
                <div style="border-bottom: 2px solid #2d3748; height: 30px; margin-bottom: 5px;"></div>
                <strong style="font-size: 10px;">Customer Signature</strong><br>
                <small style="color: #6c757d; font-size: 8px;">{{ $installmentSale->customer_name }}</small>
            </td>
            <td style="width: 5%;"></td>
            <td class="signature-cell" style="width: 30%;">
                <div style="border-bottom: 2px solid #2d3748; height: 30px; margin-bottom: 5px;"></div>
                <strong style="font-size: 10px;">Guarantor Signature</strong><br>
                <small style="color: #6c757d; font-size: 8px;">{{ $installmentSale->guarantor_name }}</small>
            </td>
            <td style="width: 5%;"></td>
            <td class="signature-cell" style="width: 30%;">
                <div style="border-bottom: 2px solid #2d3748; height: 30px; margin-bottom: 5px;"></div>
                <strong style="font-size: 10px;">Authorized Signature</strong><br>
                <small style="color: #6c757d; font-size: 8px;">Mariya Electronics</small>
            </td>
        </tr>
    </table>

    <div class="footer-text" style="margin-top: 8px;">
        This payment schedule is a part of Invoice {{ $installmentSale->installment_sale_number }} and must be kept for record purposes.
    </div>
</body>
</html>
