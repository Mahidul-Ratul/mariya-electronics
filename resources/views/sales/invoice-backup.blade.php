@extends('layout.app')

@section('title', 'Invoice - ' . $sale->sale_number)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Invoice</h2>
                    <p class="text-muted mb-0">Sale #{{ $sale->sale_number }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Sales
                    </a>
                    <a href="{{ route('sales.invoice.download', $sale) }}" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>

            <!-- Invoice Content -->
            <div class="card shadow-sm" id="invoice-content">
                <div class="card-body p-5">
                    <!-- Invoice Header -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/me_logo2.png') }}" alt="Mariya Electronics Logo" 
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px;" class="me-3">
                                <div>
                                    <h3 class="fw-bold text-primary mb-1">MARIYA ELECTRONICS</h3>
                                    <p class="text-muted mb-0">Electronics & Appliances Store</p>
                                    <p class="text-muted mb-0">Phone: +880-XXX-XXXXXXX</p>
                                    <p class="text-muted mb-0">Email: info@mariyaelectronics.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <h2 class="fw-bold text-dark mb-3">INVOICE</h2>
                            <div class="mb-2">
                                <strong>Invoice No:</strong> {{ $sale->sale_number }}
                            </div>
                            <div class="mb-2">
                                <strong>Date:</strong> {{ $sale->sale_date->format('M d, Y') }}
                            </div>
                            <div class="mb-2">
                                <strong>Payment Type:</strong> 
                                <span class="badge bg-success">{{ ucfirst($sale->payment_type) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5 class="fw-bold text-primary mb-3">Bill To:</h5>
                                <div class="mb-2">
                                    <strong>Name:</strong> {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Walk-in Customer') }}
                                </div>
                                <div class="mb-2">
                                    <strong>Phone:</strong> {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? 'N/A') }}
                                </div>
                                @if($sale->customer && $sale->customer->address || $sale->customer_address)
                                <div class="mb-2">
                                    <strong>Address:</strong> {{ $sale->customer ? $sale->customer->address : $sale->customer_address }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5 class="fw-bold text-primary mb-3">Shop Details:</h5>
                                <div class="mb-2">
                                    <strong>Mariya Electronics</strong>
                                </div>
                                <div class="mb-2">
                                    Address: Your Shop Address Here
                                </div>
                                <div class="mb-2">
                                    Phone: +880-XXX-XXXXXXX
                                </div>
                                <div class="mb-2">
                                    Email: info@mariyaelectronics.com
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Product Description</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
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
                                                <br><small class="text-muted">Brand: {{ $product['brand'] }}</small>
                                            @endif
                                            @if(isset($product['model']) && $product['model'])
                                                <br><small class="text-muted">Model: {{ $product['model'] }}</small>
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
                                                <br><small class="text-muted">Brand: {{ $sale->product->brand }}</small>
                                            @endif
                                            @if($sale->product && $sale->product->model)
                                                <br><small class="text-muted">Model: {{ $sale->product->model }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $sale->quantity }}</td>
                                        <td class="text-end">৳{{ number_format((float)$sale->unit_price, 2) }}</td>
                                        <td class="text-end">৳{{ number_format((float)$sale->unit_price * (int)$sale->quantity, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <table class="table table-borderless">
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
                                    <th class="fs-5">Total Amount:</th>
                                    <td class="text-end fs-5 fw-bold text-success">৳{{ number_format((float)$sale->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Paid Amount:</th>
                                    <td class="text-end">৳{{ number_format((float)$sale->paid_amount, 2) }}</td>
                                </tr>
                                @if((float)$sale->total_amount - (float)$sale->paid_amount > 0)
                                <tr>
                                    <th>Due Amount:</th>
                                    <td class="text-end text-warning fw-bold">৳{{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($sale->notes)
                    <!-- Notes -->
                    <div class="mt-4">
                        <h6 class="fw-bold">Notes:</h6>
                        <p class="text-muted">{{ $sale->notes }}</p>
                    </div>
                    @endif

                    <!-- Footer -->
                    <div class="row mt-5 pt-4 border-top">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Terms & Conditions:</h6>
                            <ul class="text-muted small">
                                <li>All sales are final</li>
                                <li>Warranty as per manufacturer's policy</li>
                                <li>For any queries, contact us at the above number</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="mt-4 pt-3">
                                <div style="border-top: 1px solid #000; width: 200px; margin-left: auto;">
                                    <p class="mb-0 mt-2 text-center"><strong>Authorized Signature</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">Thank you for your business!</p>
                        <p class="text-muted small mb-0">Visit us again for quality electronics and appliances</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, .navbar, .sidebar {
        display: none !important;
    }
    
    #invoice-content {
        box-shadow: none !important;
        border: none !important;
    }
    
    body {
        background-color: white !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>
@endsection