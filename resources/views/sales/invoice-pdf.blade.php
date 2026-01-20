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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: white;
            margin: 0;
            padding: 0;
        }

        @media print {
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            body > div {
                padding: 20px !important;
                background: white !important;
            }
            
            body > div > div {
                box-shadow: none !important;
                max-width: 100% !important;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            @page {
                margin: 0.5in;
                size: A4;
            }
        }
    </style>
</head>
<body>
    <div style="padding: 50px; background: #f8f9fa;">
        <div style="max-width: 750px; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
            
            <!-- Watermark -->
            <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-15deg); z-index: -1; opacity: 0.12; pointer-events: none;">
                @if(file_exists(public_path('images/me_logo2.png')))
                    <img src="{{ asset('images/me_logo2.png') }}" alt="Watermark" style="width: 350px; height: auto;">
                @else
                    <div style="width: 280px; height: 280px; background: #2d3748; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 15px; font-size: 90px; font-family: Arial;">
                        ME
                    </div>
                @endif
            </div>
            
            <div style="position: relative; z-index: 1;">
            
            <!-- Header Section -->
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="flex: 0 0 150px; text-align: center;">
                    @if(file_exists(public_path('images/me_logo2.png')))
                        <img src="{{ asset('images/me_logo2.png') }}" alt="Mariya Electronics Logo" style="height: 140px; width: auto;">
                    @else
                        <div style="width: 140px; height: 140px; background: #2d3748; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 8px; font-size: 32px; margin: 0 auto;">
                            ME
                        </div>
                    @endif
                </div>
                
                <!-- Contact Information -->
                <div style="flex: 1; text-align: right; padding-left: 25px;">
                    <div style="font-size: 12px; color: #495057; line-height: 1.6;">
                        <div style="margin-bottom: 4px;"><strong>Address:</strong> Walton Mor, Bashergoli, Demra, Dhaka</div>
                        <div style="margin-bottom: 2px;"><strong>Mobile:</strong> +123-456-7890</div>
                        <div style="margin-bottom: 2px;"><strong>Mobile:</strong> +123-456-7890</div>
                        <div><strong>Mobile:</strong> +123-456-7890</div>
                    </div>
                </div>
            </div>
            
            <!-- Simple Line -->
            <hr style="border: 1px solid #2d3748; margin: 5px 0;">

            <!-- Bill To and Invoice Info -->
            <div style="display: flex; margin: 20px 0;">
                <div style="flex: 1; padding-right: 25px;">
                    <h4 style="color: #2d3748; font-size: 14px; margin-bottom: 10px; font-weight: 600;">Bill To:</h4>
                    <div style="font-size: 12px; color: #4A5568; line-height: 1.4;">
                        <div style="margin-bottom: 5px;"><strong>Name:</strong> {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Mahidul Ratul') }}</div>
                        <div style="margin-bottom: 5px;"><strong>Address:</strong> {{ $sale->customer ? $sale->customer->address : ($sale->customer_address ?? 'hhuh gyhj') }}</div>
                        <div style="margin-bottom: 5px;"><strong>Mobile No.:</strong> {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? '01797778412') }}</div>
                    </div>
                </div>
                
                <div style="flex: 1; text-align: right;">
                    <div style="font-size: 12px; color: #4A5568; line-height: 1.5;">
                        <div style="margin-bottom: 5px;"><strong>INVOICE NO:</strong> {{ $sale->sale_number }}</div>
                        <div style="margin-bottom: 5px;"><strong>DATE:</strong> {{ $sale->sale_date->format('d/m/Y') }}</div>
                        <div style="margin-bottom: 5px;"><strong>PAYMENT TYPE:</strong> <span style="background: #38A169; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 600;">{{ strtoupper($sale->payment_type) }}</span></div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
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
                        <tr style="background: white; border-bottom: 1px solid #E2E8F0;">
                            <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">01</td>
                            <td style="padding: 8px 6px; font-size: 11px; color: #2D3748;">
                                @if($sale->product)
                                    {{ $sale->product->name }}
                                    @if($sale->product->brand)
                                        <br><small style="color: #6c757d; font-size: 9px;">Brand: {{ $sale->product->brand }}</small>
                                    @endif
                                    @if($sale->product->model)
                                        <br><small style="color: #6c757d; font-size: 9px;">Model: {{ $sale->product->model }}</small>
                                    @endif
                                @elseif($sale->products_data && isset($sale->products_data['name']))
                                    {{ $sale->products_data['name'] }}
                                    @if(isset($sale->products_data['brand']) && $sale->products_data['brand'])
                                        <br><small style="color: #6c757d; font-size: 9px;">Brand: {{ $sale->products_data['brand'] }}</small>
                                    @endif
                                    @if(isset($sale->products_data['model']) && $sale->products_data['model'])
                                        <br><small style="color: #6c757d; font-size: 9px;">Model: {{ $sale->products_data['model'] }}</small>
                                    @endif
                                @else
                                    Washing Machine
                                    <br><small style="color: #6c757d; font-size: 9px;">Brand: Walton</small>
                                    <br><small style="color: #6c757d; font-size: 9px;">Model: WM120</small>
                                @endif
                            </td>
                            <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">{{ $sale->quantity ?? 1 }}</td>
                            <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">৳ {{ number_format((float)$sale->unit_price, 2) }}</td>
                            <td style="text-align: center; padding: 8px 6px; font-size: 11px; color: #4A5568;">৳ {{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes and Totals -->
            <div style="display: flex; margin-top: 30px;">
                <div style="flex: 1; padding-right: 25px;">
                    <h5 style="color: #4A90E2; font-size: 13px; margin-bottom: 8px; font-weight: 600;">Special Notes:</h5>
                    <p style="font-size: 10px; line-height: 1.4; color: #718096; text-align: justify;">
                        {{ $sale->notes ?? 'hkl;;hgg' }}
                    </p>
                </div>
                
                <div style="flex: 1;">
                    <div style="border: 1px solid #dee2e6; padding: 18px; border-radius: 8px;">
                        <table style="width: 100%; font-size: 12px;">
                            <tr>
                                <td style="padding: 6px 0; color: #495057; font-weight: 500;">Sub Total:</td>
                                <td style="text-align: right; padding: 6px 0; color: #212529; font-weight: 600;">৳ {{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)($sale->quantity ?? 1)), 2) }}</td>
                            </tr>
                            @if($sale->discount_amount > 0)
                            <tr>
                                <td style="padding: 6px 0; color: #dc3545; font-weight: 500;">Discount:</td>
                                <td style="text-align: right; padding: 6px 0; color: #dc3545; font-weight: 600;">-৳ {{ number_format((float)$sale->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr style="border-top: 2px solid #2d3748;">
                                <td style="padding: 10px 0 6px 0; color: #2d3748; font-size: 14px; font-weight: 700;">Total Amount:</td>
                                <td style="text-align: right; padding: 10px 0 6px 0; color: #2d3748; font-size: 14px; font-weight: 700;">৳ {{ number_format((float)$sale->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 4px 0; color: #495057; font-weight: 500;">Paid Amount:</td>
                                <td style="text-align: right; padding: 4px 0; color: #212529; font-weight: 600;">৳ {{ number_format((float)$sale->paid_amount, 2) }}</td>
                            </tr>
                            @if((float)$sale->total_amount - (float)$sale->paid_amount > 0)
                            <tr>
                                <td style="padding: 4px 0; color: #fd7e14; font-weight: 500;">Due Amount:</td>
                                <td style="text-align: right; padding: 4px 0; color: #fd7e14; font-weight: 600;">৳ {{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Signature Section -->
            <div style="margin-top: 40px; padding-top: 25px; border-top: 2px solid #2d3748;">
                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <!-- Customer Signature -->
                    <div style="flex: 1; text-align: center; margin-right: 50px;">
                        <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 25px; min-height: 50px;"></div>
                        <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Customer Signature</p>
                        <p style="font-size: 10px; color: #6c757d; margin: 0;">{{ $sale->customer->name ?? 'Customer' }}</p>
                    </div>
                    
                    <!-- Seller Signature -->
                    <div style="flex: 1; text-align: center; margin-left: 50px;">
                        <div style="border-bottom: 2px solid #2d3748; margin-bottom: 8px; padding-bottom: 25px; min-height: 50px;"></div>
                        <p style="font-size: 12px; color: #495057; margin: 0; font-weight: 600;">Authorized Signature</p>
                        <p style="font-size: 10px; color: #6c757d; margin: 0;">Mariya Electronics</p>
                    </div>
                </div>
                
                <!-- Warranty & Support Information -->
                <div style="display: flex; justify-content: space-between; margin: 25px 0 20px 0; font-size: 11px; color: #495057;">
                    <div style="flex: 1; padding-right: 15px;">
                        <h4 style="color: #2d3748; font-size: 12px; margin-bottom: 8px; font-weight: 600;">📞 Customer Support</h4>
                        <p style="margin: 0; line-height: 1.4;">For any queries or support, please contact us at our mobile numbers above. Our team is ready to assist you.</p>
                    </div>
                    <div style="flex: 1; padding-left: 15px;">
                        <h4 style="color: #2d3748; font-size: 12px; margin-bottom: 8px; font-weight: 600;">🔧 Warranty & Service</h4>
                        <p style="margin: 0; line-height: 1.4;">All products come with manufacturer warranty. Keep this invoice for warranty claims and future reference.</p>
                    </div>
                </div>
                
                <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #dee2e6;">
                    <p style="color: #2d3748; font-size: 11px; margin: 0 0 8px 0; font-weight: 600;">💡 Why Choose Mariya Electronics?</p>
                    <div style="display: flex; justify-content: space-around; font-size: 10px; color: #495057;">
                        <span>✓ Quality Products</span>
                        <span>✓ Best Prices</span>
                        <span>✓ Expert Service</span>
                        <span>✓ Quick Delivery</span>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 15px;">
                    <p style="color: #6c757d; font-size: 10px; margin: 0; font-style: italic;">Visit us again for all your electronics and appliances needs • Trusted since establishment</p>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>