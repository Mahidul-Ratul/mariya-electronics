@extends('layout.app')

@section('title', 'Invoice - ' . $installmentSale->installment_sale_number)

@push('styles')
<style>
@media print {
    body .sidebar, body .col-md-3.sidebar, body .col-lg-2.sidebar, body div.sidebar, body .top-navbar, body .sidebar-overlay, body .navbar, body .nav, body .breadcrumb, body .alert, body .dropdown, body .mobile-menu-btn, body .notification-bell, body .page-title, body .page-subtitle, body .no-print, body .btn, body button, body nav, body header, .container-fluid .sidebar, .row .sidebar, .col-md-3, .col-lg-2 {
        display: none !important; visibility: hidden !important; width: 0 !important; height: 0 !important; overflow: hidden !important; position: absolute !important; left: -9999px !important;
    }
    body { margin: 0 !important; padding: 0 !important; background: white !important; background-color: white !important; background-image: none !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif !important; }
    .container-fluid, .container, .row { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; background: white !important; }
    .main-content, .col-md-9, .col-lg-10 { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; flex: none !important; position: static !important; display: block !important; background: white !important; }
    .container-fluid.p-4, .p-4, .p-3, .p-2, .p-1 { padding: 0 !important; }
    #invoice-content { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 15px !important; background: white !important; position: relative !important; box-shadow: none !important; border: none !important; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    @page { margin: 0.5in; size: A4; }
}
#invoice-content { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; }
</style>
@endpush

@section('content')
<div class="container-fluid mb-4 no-print">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Invoice {{ $installmentSale->installment_sale_number }}</h4>
                <div>
                    <a href="{{ route('installment-sales.invoice.download', $installmentSale) }}" class="btn btn-primary me-2"><i class="fas fa-download"></i> Download PDF</a>
                    <a href="{{ route('installment-sales.show', $installmentSale) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Sale</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="invoice-content">
<div style="padding: 50px; background: #f8f9fa;">
    <div style="max-width: 750px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
        <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-15deg); z-index: -1; opacity: 0.12; pointer-events: none;">
            @if(file_exists(public_path('images/me_logo2.png')))
                <img src="{{ asset('images/me_logo2.png') }}" alt="Watermark" style="width: 350px; height: auto;">
            @else
                <div style="width: 280px; height: 280px; background: #2d3748; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 15px; font-size: 90px; font-family: Arial;">ME</div>
            @endif
        </div>
        
        <div style="position: relative; z-index: 1;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="flex: 0 0 150px; text-align: center;">
                @if(file_exists(public_path('images/me_logo2.png')))
                    <img src="{{ asset('images/me_logo2.png') }}" alt="Mariya Electronics Logo" style="height: 140px; width: auto;">
                @else
                    <div style="width: 140px; height: 140px; background: #2d3748; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 8px; font-size: 32px; margin: 0 auto;">ME</div>
                @endif
            </div>
            <div style="flex: 1; text-align: right; padding-left: 25px;">
                <div style="font-size: 12px; color: #495057; line-height: 1.6;">
                    <div style="margin-bottom: 4px;"><strong>Address:</strong> Walton Mor, Bashergoli, Demra, Dhaka</div>
                    <div style="margin-bottom: 2px;"><strong>Mobile:</strong> +123-456-7890</div>
                    <div style="margin-bottom: 2px;"><strong>Mobile:</strong> +123-456-7890</div>
                    <div><strong>Mobile:</strong> +123-456-7890</div>
                </div>
            </div>
        </div>
        
        <hr style="border: 1px solid #2d3748; margin: 5px 0;">

        <div style="display: flex; margin: 20px 0;">
            <div style="flex: 1; padding-right: 25px;">
                <h4 style="color: #2d3748; font-size: 14px; margin-bottom: 10px; font-weight: 600;">Bill To:</h4>
                <div style="font-size: 12px; color: #4A5568; line-height: 1.4;">
                    <div style="margin-bottom: 5px;"><strong>Name:</strong> {{ $installmentSale->customer_name }}</div>
                    <div style="margin-bottom: 5px;"><strong>Address:</strong> {{ $installmentSale->customer_address }}</div>
                    <div style="margin-bottom: 5px;"><strong>Mobile No.:</strong> {{ $installmentSale->customer_mobile }}</div>
                </div>
            </div>
            <div style="flex: 1; text-align: right;">
                <div style="font-size: 12px; color: #4A5568; line-height: 1.5;">
                    <div style="margin-bottom: 5px;"><strong>INVOICE NO:</strong> {{ $installmentSale->installment_sale_number }}</div>
                    <div style="margin-bottom: 5px;"><strong>DATE:</strong> {{ \Carbon\Carbon::parse($installmentSale->sale_date)->format('d/m/Y') }}</div>
                    <div style="margin-bottom: 5px;"><strong>PAYMENT TYPE:</strong> <span style="background: #38A169; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 600;">INSTALLMENT</span></div>
                </div>
            </div>
        </div>

        <div style="margin: 20px 0;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: #2d3748;">
                        <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; width: 8%; border: none;">#</th>
                        <th style="color: white; padding: 12px 8px; font-size: 13px; font-weight: 600; width: 50%; border: none;">Product Description</th>
                        <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; width: 12%; border: none;">Qty</th>
                        <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; width: 15%; border: none;">Unit Price</th>
                        <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; width: 15%; border: none;">Total</th>
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
                    <tr style="background: white; border-bottom: 1px solid #E2E8F0;">
                        <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 8px 6px; font-size: 11px; color: #2D3748;">
                            {{ $product['product_name'] ?? 'Product' }}
                            @if(isset($product['brand']) && $product['brand'])
                                <br><small style="color: #6c757d; font-size: 9px;">Brand: {{ $product['brand'] }}</small>
                            @endif
                            @if(isset($product['model']) && $product['model'])
                                <br><small style="color: #6c757d; font-size: 9px;">Model: {{ $product['model'] }}</small>
                            @endif
                        </td>
                        <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">{{ $product['quantity'] ?? 1 }}</td>
                        <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">৳ {{ number_format((float)($product['unit_price'] ?? 0), 2) }}</td>
                        <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">৳ {{ number_format((float)($product['unit_price'] ?? 0) * (int)($product['quantity'] ?? 1), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="display: flex; margin-top: 30px;">
            <div style="flex: 1; padding-right: 25px;">
                <h5 style="color: #4A90E2; font-size: 13px; margin-bottom: 8px; font-weight: 600;">Special Notes:</h5>
                <p style="font-size: 10px; line-height: 1.4; color: #718096; text-align: justify;">{{ $installmentSale->notes ?? 'N/A' }}</p>
            </div>
            <div style="flex: 1;">
                <div style="border: 1px solid #dee2e6; padding: 18px; border-radius: 8px;">
                    <table style="width: 100%; font-size: 12px;">
                        <tr><td style="padding: 6px 0; color: #495057; font-weight: 500;">Sub Total:</td><td style="text-align: right; padding: 6px 0; color: #212529; font-weight: 600;">৳ {{ number_format($installmentSale->subtotal, 2) }}</td></tr>
                        @if($installmentSale->discount_amount > 0)
                        <tr><td style="padding: 6px 0; color: #dc3545; font-weight: 500;">Discount:</td><td style="text-align: right; padding: 6px 0; color: #dc3545; font-weight: 600;">-৳ {{ number_format((float)$installmentSale->discount_amount, 2) }}</td></tr>
                        @endif
                        <tr style="border-top: 2px solid #2d3748;"><td style="padding: 10px 0 6px 0; color: #2d3748; font-size: 14px; font-weight: 700;">Total Amount:</td><td style="text-align: right; padding: 10px 0 6px 0; color: #2d3748; font-size: 14px; font-weight: 700;">৳ {{ number_format((float)$installmentSale->total_amount, 2) }}</td></tr>
                        <tr><td style="padding: 4px 0; color: #495057; font-weight: 500;">Down Payment:</td><td style="text-align: right; padding: 4px 0; color: #212529; font-weight: 600;">৳ {{ number_format((float)$installmentSale->down_payment, 2) }}</td></tr>
                        <tr><td style="padding: 4px 0; color: #fd7e14; font-weight: 500;">Remaining Amount:</td><td style="text-align: right; padding: 4px 0; color: #fd7e14; font-weight: 600;">৳ {{ number_format((float)$installmentSale->total_amount - (float)$installmentSale->down_payment, 2) }}</td></tr>
                        <tr style="border-top: 1px solid #dee2e6;"><td style="padding: 6px 0 2px 0; color: #17a2b8; font-weight: 600;">Monthly Installment:</td><td style="text-align: right; padding: 6px 0 2px 0; color: #17a2b8; font-weight: 700; font-size: 13px;">৳ {{ number_format((float)$installmentSale->monthly_installment, 2) }}</td></tr>
                        <tr><td style="padding: 2px 0; color: #6c757d; font-size: 11px;">Total Months:</td><td style="text-align: right; padding: 2px 0; color: #6c757d; font-size: 11px;">{{ $installmentSale->total_installments }} months</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div style="margin-top: 40px; padding-top: 25px; border-top: 2px solid #2d3748;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                <div style="flex: 1; text-align: center; margin-right: 50px;">
                    <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 25px; min-height: 50px;"></div>
                    <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Customer Signature</p>
                    <p style="font-size: 10px; color: #6c757d; margin: 0;">{{ $installmentSale->customer_name }}</p>
                </div>
                <div style="flex: 1; text-align: center; margin-left: 50px;">
                    <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 25px; min-height: 50px;"></div>
                    <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Authorized Signature</p>
                    <p style="font-size: 10px; color: #6c757d; margin: 0;">Mariya Electronics</p>
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin: 25px 0 20px 0; font-size: 11px; color: #495057;">
                <div style="flex: 1; padding-right: 15px;">
                    <h4 style="color: #2d3748; font-size: 12px; margin-bottom: 8px; font-weight: 600;">📞 Customer Support</h4>
                    <p style="margin: 0; line-height: 1.4;">For any queries or support, please contact us at our mobile numbers above. Our team is ready to assist you.</p>
                </div>
                <div style="flex: 1; padding-left: 15px;">
                    <h4 style="color: #2d3748; font-size: 12px; margin-bottom: 8px; font-weight: 600;">📅 Installment Terms</h4>
                    <p style="margin: 0; line-height: 1.4;">Please ensure timely payment of monthly installments. Keep this invoice for payment records and reference.</p>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #dee2e6;">
                <p style="color: #2d3748; font-size: 11px; margin: 0 0 8px 0; font-weight: 600;">💡 Why Choose Mariya Electronics?</p>
                <div style="display: flex; justify-content: space-around; font-size: 10px; color: #495057;">
                    <span>✓ Quality Products</span><span>✓ Best Prices</span><span>✓ Expert Service</span><span>✓ Quick Delivery</span>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 15px;">
                <p style="color: #6c757d; font-size: 10px; margin: 0; font-style: italic;">Visit us again for all your electronics and appliances needs • Trusted since establishment</p>
            </div>
        </div>
        </div>
    </div>

    <!-- Page 2: Installment Schedule -->
    <div style="max-width: 750px; margin: 40px auto 0; background: white; padding: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); page-break-before: always;">
        <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-15deg); z-index: -1; opacity: 0.12; pointer-events: none;">
            @if(file_exists(public_path('images/me_logo2.png')))
                <img src="{{ asset('images/me_logo2.png') }}" alt="Watermark" style="width: 350px; height: auto;">
            @else
                <div style="width: 280px; height: 280px; background: #2d3748; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 15px; font-size: 90px; font-family: Arial;">ME</div>
            @endif
        </div>

        <div style="position: relative; z-index: 1;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d3748; padding-bottom: 15px;">
            <h2 style="color: #2d3748; font-size: 24px; margin: 0 0 5px 0; font-weight: 700;">INSTALLMENT PAYMENT SCHEDULE</h2>
            <p style="color: #6c757d; font-size: 12px; margin: 0;">Invoice: {{ $installmentSale->installment_sale_number }}</p>
        </div>

        <!-- Customer Info Summary -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px; background: #f8f9fa; padding: 15px; border-radius: 8px;">
            <div style="flex: 1;">
                <div style="font-size: 12px; line-height: 1.8;">
                    <div><strong style="color: #2d3748;">Customer:</strong> {{ $installmentSale->customer_name }}</div>
                    <div><strong style="color: #2d3748;">Mobile:</strong> {{ $installmentSale->customer_mobile }}</div>
                </div>
            </div>
            <div style="flex: 1; text-align: right;">
                <div style="font-size: 12px; line-height: 1.8;">
                    <div><strong style="color: #2d3748;">Total Amount:</strong> ৳{{ number_format($installmentSale->total_amount, 2) }}</div>
                    <div><strong style="color: #2d3748;">Down Payment:</strong> ৳{{ number_format($installmentSale->down_payment, 2) }}</div>
                    <div><strong style="color: #17a2b8;">Monthly:</strong> ৳{{ number_format($installmentSale->monthly_installment, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Installment Schedule Table -->
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;">
            <thead>
                <tr style="background: #2d3748;">
                    <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; border: none;">Installment #</th>
                    <th style="color: white; padding: 12px 8px; font-size: 13px; font-weight: 600; border: none;">Due Date</th>
                    <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; border: none;">Amount</th>
                    <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; border: none;">Status</th>
                    <th style="color: white; text-align: center; padding: 12px 8px; font-size: 13px; font-weight: 600; width: 150px; border: none;">Signature</th>
                </tr>
            </thead>
            <tbody>
                @foreach($installmentSchedule as $installment)
                <tr style="background: white; border-bottom: 1px solid #E2E8F0;">
                    <td style="text-align: center; padding: 10px 8px; font-size: 12px; color: #2d3748; font-weight: 600;">{{ $installment['number'] }}</td>
                    <td style="padding: 10px 8px; font-size: 12px; color: #4A5568;">{{ $installment['due_date']->format('d M Y') }}</td>
                    <td style="text-align: center; padding: 10px 8px; font-size: 12px; color: #2d3748; font-weight: 600;">৳{{ number_format($installment['amount'], 2) }}</td>
                    <td style="text-align: center; padding: 10px 8px;">
                        <span style="background: #ffc107; color: #333; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600;">{{ ucfirst($installment['status']) }}</span>
                    </td>
                    <td style="text-align: center; padding: 10px 8px;">
                        <div style="border-bottom: 1px solid #999; width: 120px; margin: 0 auto; height: 20px;"></div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Terms & Conditions -->
        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
            <h4 style="color: #856404; font-size: 13px; margin: 0 0 10px 0; font-weight: 600;">⚠️ Important Terms & Conditions:</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 11px; line-height: 1.6; color: #856404;">
                <li>All installments must be paid on or before the due date mentioned above.</li>
                <li>Late payment charges may apply for overdue payments.</li>
                <li>The customer and guarantor are jointly responsible for all payments.</li>
                <li>Products remain the property of Mariya Electronics until full payment is received.</li>
                <li>Any default in payment may result in repossession of the product.</li>
                <li>This payment schedule is subject to the terms agreed upon at the time of sale.</li>
            </ul>
        </div>

        <!-- Signatures -->
        <div style="display: flex; justify-content: space-between; margin-top: 60px; padding-top: 20px; border-top: 2px solid #2d3748;">
            <div style="flex: 1; text-align: center; margin-right: 30px;">
                <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 30px; min-height: 40px;"></div>
                <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Customer Signature</p>
                <p style="font-size: 10px; color: #6c757d; margin: 0;">{{ $installmentSale->customer_name }}</p>
            </div>
            <div style="flex: 1; text-align: center; margin: 0 30px;">
                <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 30px; min-height: 40px;"></div>
                <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Guarantor Signature</p>
                <p style="font-size: 10px; color: #6c757d; margin: 0;">{{ $installmentSale->guarantor_name }}</p>
            </div>
            <div style="flex: 1; text-align: center; margin-left: 30px;">
                <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 30px; min-height: 40px;"></div>
                <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Authorized Signature</p>
                <p style="font-size: 10px; color: #6c757d; margin: 0;">Mariya Electronics</p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #6c757d; font-size: 10px; margin: 0; font-style: italic;">This payment schedule is a part of Invoice {{ $installmentSale->installment_sale_number }} and must be kept for record purposes.</p>
        </div>
        </div>
    </div>

    <div style="text-align: center; padding: 20px;" class="d-print-none">
        <a href="{{ route('installment-sales.invoice.download', $installmentSale) }}" style="background: #6c63ff; color: white; border: none; padding: 10px 25px; border-radius: 25px; margin: 0 10px; text-decoration: none; display: inline-block;">📥 Download PDF</a>
        <a href="{{ route('installment-sales.show', $installmentSale) }}" style="background: #6c757d; color: white; padding: 10px 25px; border-radius: 25px; text-decoration: none; margin: 0 10px;">← Back to Sale</a>
    </div>
</div>
</div>
@endsection
