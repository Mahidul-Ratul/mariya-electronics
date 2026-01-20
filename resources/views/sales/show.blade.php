@extends('layout.app')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Sale Details</h2>
                    <p class="text-muted mb-0">Sale #{{ $sale->sale_number }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Sales
                    </a>
                    @if($sale->payment_type === 'cash')
                    <a href="{{ route('sales.invoice', $sale) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-eye me-2"></i>View Invoice
                    </a>
                    <a href="{{ route('sales.invoice.download', $sale) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                    @endif
                    <a href="{{ route('sales.edit', $sale) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Sale
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Sale Information -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 fw-bold">Sale Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Sale Number</label>
                                        <p class="fw-bold mb-0">#{{ $sale->sale_number }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Sale Date</label>
                                        <p class="mb-0">{{ $sale->sale_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Payment Type</label>
                                        <p class="mb-0">
                                            <span class="badge {{ $sale->payment_type === 'cash' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $sale->payment_type === 'cash' ? 'success' : 'warning' }}">
                                                {{ ucfirst($sale->payment_type) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status</label>
                                        <p class="mb-0">
                                            <span class="badge 
                                                @if($sale->status === 'completed') bg-success
                                                @elseif($sale->status === 'pending') bg-warning
                                                @else bg-danger
                                                @endif">
                                                {{ ucfirst($sale->status) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Total Amount</label>
                                        <p class="fw-bold mb-0 text-success h5">৳{{ number_format($sale->total_amount, 2) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Paid Amount</label>
                                        <p class="fw-bold mb-0 text-primary">৳{{ number_format($sale->paid_amount, 2) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Due Amount</label>
                                        <p class="fw-bold mb-0 {{ ((float)$sale->total_amount - (float)$sale->paid_amount) > 0 ? 'text-danger' : 'text-success' }}">
                                            ৳{{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}
                                        </p>
                                    </div>
                                    @if($sale->payment_type === 'installment')
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Installment Months</label>
                                        <p class="mb-0">{{ $sale->installment_months }} months</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 fw-bold">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Customer Name</label>
                                        <p class="fw-bold mb-0">
                                            {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Direct Sale') }}
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Phone</label>
                                        <p class="mb-0">
                                            {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? 'N/A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($sale->customer && $sale->customer->email)
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <p class="mb-0">{{ $sale->customer->email }}</p>
                                    </div>
                                    @endif
                                    @if($sale->customer && $sale->customer->address || $sale->customer_address)
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Address</label>
                                        <p class="mb-0">{{ $sale->customer ? $sale->customer->address : $sale->customer_address }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($sale->guarantor_name)
                            <hr>
                            <h6 class="fw-bold mb-3">Guarantor Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Guarantor Name</label>
                                        <p class="mb-0">{{ $sale->guarantor_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Guarantor Phone</label>
                                        <p class="mb-0">{{ $sale->guarantor_mobile }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 fw-bold">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Single product display -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $productsList = $sale->products_list ?? [];
                                        @endphp
                                        
                                        @if(count($productsList) > 0)
                                            @foreach($productsList as $index => $product)
                                            <tr>
                                                <td>
                                                    <strong>{{ $product['product_name'] ?? 'Product' }}</strong>
                                                    @if(isset($product['brand']) && $product['brand'])
                                                        <br><small class="text-muted">Brand: {{ $product['brand'] }}</small>
                                                    @endif
                                                    @if(isset($product['model']) && $product['model'])
                                                        <br><small class="text-muted">Model: {{ $product['model'] }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $product['quantity'] ?? 1 }}</td>
                                                <td>৳{{ number_format((float)($product['unit_price'] ?? 0), 2) }}</td>
                                                <td>৳{{ number_format((float)($product['unit_price'] ?? 0) * (int)($product['quantity'] ?? 1), 2) }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <strong>
                                                        @if($sale->product && $sale->product->name)
                                                            {{ $sale->product->name }}
                                                        @else
                                                            Product
                                                        @endif
                                                    </strong>
                                                </td>
                                                <td>{{ $sale->quantity ?? 1 }}</td>
                                                <td>৳{{ number_format((float)($sale->unit_price ?? 0), 2) }}</td>
                                                <td>৳{{ number_format((float)($sale->unit_price ?? 0) * (int)($sale->quantity ?? 1), 2) }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                                
                            <!-- Summary -->
                            <hr>
                            <div class="row">
                                <div class="col-md-6 ms-auto">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td class="text-end">৳{{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)($sale->quantity ?? 1)), 2) }}</td>
                                        </tr>
                                        @if($sale->discount_amount > 0)
                                        <tr>
                                            <td><strong>Discount:</strong></td>
                                            <td class="text-end text-danger">-৳{{ number_format((float)$sale->discount_amount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Total:</strong></td>
                                            <td class="text-end"><strong>৳{{ number_format((float)$sale->total_amount, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Paid Amount:</strong></td>
                                            <td class="text-end text-primary"><strong>৳{{ number_format($sale->paid_amount, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Due Amount:</strong></td>
                                            <td class="text-end {{ ((float)$sale->total_amount - (float)$sale->paid_amount) > 0 ? 'text-danger' : 'text-success' }}">
                                                <strong>৳{{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($sale->notes)
                    <!-- Notes -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 fw-bold">Notes</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $sale->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    @if($sale->payment_type === 'installment' && $sale->installments->count() > 0)
                    <!-- Installments -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">Installments</h5>
                            <small class="text-muted">{{ $sale->installments->count() }} installments</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($sale->installments as $installment)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Installment #{{ $installment->installment_number }}</h6>
                                            <small class="text-muted">Due: {{ $installment->due_date->format('M d, Y') }}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">৳{{ number_format($installment->amount, 2) }}</div>
                                            <span class="badge 
                                                @if($installment->status === 'paid') bg-success
                                                @elseif($installment->status === 'overdue') bg-danger
                                                @else bg-warning
                                                @endif">
                                                {{ ucfirst($installment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($installment->fine_amount > 0)
                                    <small class="text-danger">Fine: ৳{{ number_format($installment->fine_amount, 2) }}</small>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($sale->payments->count() > 0)
                    <!-- Payment History -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0 fw-bold">Payment History</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($sale->payments as $payment)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Payment #{{ $payment->id }}</h6>
                                            <small class="text-muted">{{ $payment->payment_date->format('M d, Y h:i A') }}</small>
                                        </div>
                                        <div class="fw-bold text-success">৳{{ number_format($payment->amount, 2) }}</div>
                                    </div>
                                    @if($payment->payment_method)
                                    <small class="text-muted">Method: {{ ucfirst($payment->payment_method) }}</small>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection