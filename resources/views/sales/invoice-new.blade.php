@extends('layout.app')

@section('title', 'Invoice - ' . $sale->sale_number)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0" id="invoice-content">
                <div class="card-body p-0">
                    <!-- Header Section -->
                    <div class="bg-white p-4">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    @if(file_exists(public_path('images/me_logo2.png')))
                                        <img src="{{ asset('images/me_logo2.png') }}" alt="ME Logo" class="me-3" style="height: 60px; width: auto;">
                                    @endif
                                    <h2 class="mb-0 fw-bold" style="color: #2c5aa0;">MARIYA ELECTRONICS</h2>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div style="font-size: 14px; line-height: 1.4;">
                                    <div><strong>Address:</strong> Walton Mor, Bashergoli,</div>
                                    <div style="margin-left: 65px;">Demra, Dhaka</div>
                                    <div><strong>Mobile No.:</strong> +123-456-7890</div>
                                    <div style="margin-left: 85px;">+123-456-7890</div>
                                    <div><strong>Mobile No.:</strong> +123-456-7890</div>
                                </div>
                            </div>
                        </div>

                        <hr style="border-top: 1px solid #333; margin: 20px 0;">

                        <!-- Bill To and Invoice Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div style="font-size: 14px;">
                                    <div class="fw-bold mb-2">Bill To:</div>
                                    <div><strong>Name:</strong> {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Walk-in Customer') }}</div>
                                    <div><strong>Address:</strong> {{ $sale->customer ? $sale->customer->address : ($sale->customer_address ?? '123 Anywhere St., Any City, ST 12345') }}</div>
                                    <div><strong>Mobile No.:</strong> {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? '+123-456-7890') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div style="font-size: 14px;">
                                    <div class="mb-1"><strong>INVOICE NO:</strong> {{ $sale->sale_number }}</div>
                                    <div class="mb-1"><strong>DATE:</strong> {{ $sale->sale_date->format('d/m/Y') }}</div>
                                    <div class="mb-1"><strong>PAYMENT TYPE:</strong> {{ strtoupper($sale->payment_type) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead>
                                    <tr style="background-color: #2c5aa0; color: white;">
                                        <th style="width: 8%; text-align: center; padding: 10px; border: 1px solid #2c5aa0;">No</th>
                                        <th style="width: 52%; padding: 10px; border: 1px solid #2c5aa0;">Description</th>
                                        <th style="width: 10%; text-align: center; padding: 10px; border: 1px solid #2c5aa0;">Qty</th>
                                        <th style="width: 15%; text-align: center; padding: 10px; border: 1px solid #2c5aa0;">Price</th>
                                        <th style="width: 15%; text-align: center; padding: 10px; border: 1px solid #2c5aa0;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rowCount = 0;
                                    @endphp
                                    
                                    @if($sale->products_data && is_array($sale->products_data) && count($sale->products_data) > 0)
                                        @foreach($sale->products_data as $product)
                                        @php $rowCount++; @endphp
                                        <tr>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ str_pad($rowCount, 2, '0', STR_PAD_LEFT) }}</td>
                                            <td style="padding: 8px; border: 1px solid #ddd;">{{ $product['name'] ?? 'Product N/A' }}</td>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ $sale->quantity ?? 1 }}</td>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ number_format((float)$sale->unit_price, 0) }}</td>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 0) }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        @php $rowCount = 1; @endphp
                                        <tr>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">01</td>
                                            <td style="padding: 8px; border: 1px solid #ddd;">{{ $sale->product ? $sale->product->name : 'Product N/A' }}</td>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ $sale->quantity ?? 1 }}</td>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ number_format((float)$sale->unit_price, 0) }}</td>
                                            <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 0) }}</td>
                                        </tr>
                                    @endif
                                    
                                    <!-- Empty rows to match design -->
                                    @for($i = $rowCount + 1; $i <= 4; $i++)
                                    <tr>
                                        <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td style="padding: 8px; border: 1px solid #ddd;">&nbsp;</td>
                                        <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">&nbsp;</td>
                                        <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">&nbsp;</td>
                                        <td style="text-align: center; padding: 8px; border: 1px solid #ddd;">&nbsp;</td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals and Notes Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="fw-bold text-primary mb-2" style="color: #2c5aa0 !important;">Special Notes:</div>
                                    <div style="font-size: 11px; line-height: 1.3; color: #666;">
                                        {{ $sale->notes ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit distinctio nostrum incidunt ut libero et eaque magnis atque. Ut eaque ad nostrud velit assumenda quas incididunt agendam facere itoi et eleifendae commodo asperiores.' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <table style="width: 100%; font-size: 14px;">
                                    <tr>
                                        <td style="text-align: right; padding: 5px 15px 5px 0; font-weight: bold;">Sub Total:</td>
                                        <td style="text-align: center; border: 1px solid #ddd; padding: 5px 10px; width: 80px;">{{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)($sale->quantity ?? 1)), 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; padding: 5px 15px 5px 0; font-weight: bold;">Discount:</td>
                                        <td style="text-align: center; border: 1px solid #ddd; padding: 5px 10px;">{{ $sale->discount_amount > 0 ? number_format((float)$sale->discount_amount, 0) : '0' }}</td>
                                    </tr>
                                    <tr style="border-top: 2px solid #333;">
                                        <td style="text-align: right; padding: 8px 15px 5px 0; font-weight: bold; font-size: 16px;">Total:</td>
                                        <td style="text-align: center; border: 1px solid #ddd; padding: 8px 10px; font-weight: bold; font-size: 16px;">{{ number_format((float)$sale->total_amount, 0) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-light p-3 border-top d-print-none">
                        <div class="d-flex gap-2 justify-content-center">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="fas fa-print me-2"></i>Print Invoice
                            </button>
                            <a href="{{ route('sales.download-invoice', $sale) }}" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Sale
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
        max-width: none !important;
    }
    
    .col-lg-10 {
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
}

/* Custom styling for invoice */
#invoice-content {
    font-family: Arial, sans-serif;
}

#invoice-content table th,
#invoice-content table td {
    vertical-align: middle;
}
</style>
@endsection