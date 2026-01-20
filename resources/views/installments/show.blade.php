@extends('layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Installment Sale Details</h2>
            <p class="text-muted mb-0">View installment sale information</p>
        </div>
        <div>
            <a href="{{ route('installment-sales.invoice', $installmentSale->id) }}" 
               class="btn btn-success" target="_blank">
                <i class="fas fa-file-invoice"></i> Print Invoice
            </a>
            <a href="{{ route('installment-sales.edit', $installmentSale->id) }}" 
               class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('installment-sales.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information Card -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Sale Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Invoice Number:</strong>
                            <p class="text-primary fs-5">{{ $installmentSale->installment_sale_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Sale Date:</strong>
                            <p>{{ \Carbon\Carbon::parse($installmentSale->sale_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Payment Status:</strong>
                            <p>
                                @if($installmentSale->payment_status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($installmentSale->payment_status == 'completed')
                                    <span class="badge bg-primary">Completed</span>
                                @elseif($installmentSale->payment_status == 'defaulted')
                                    <span class="badge bg-danger">Defaulted</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($installmentSale->payment_status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Created At:</strong>
                            <p>{{ $installmentSale->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                    @if($installmentSale->notes)
                    <div class="row">
                        <div class="col-12">
                            <strong>Notes:</strong>
                            <p class="text-muted">{{ $installmentSale->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Name:</strong>
                            <p>{{ $installmentSale->customer_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Mobile:</strong>
                            <p>{{ $installmentSale->customer_mobile }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>NID:</strong>
                            <p>{{ $installmentSale->customer_nid ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Address:</strong>
                            <p>{{ $installmentSale->customer_address }}</p>
                        </div>
                    </div>

                    @if($installmentSale->customer_wife_name)
                    <hr>
                    <h6 class="text-muted">Spouse Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Spouse Name:</strong>
                            <p>{{ $installmentSale->customer_wife_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Spouse NID:</strong>
                            <p>{{ $installmentSale->customer_wife_nid ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guarantor Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Guarantor Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Name:</strong>
                            <p>{{ $installmentSale->guarantor_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Mobile:</strong>
                            <p>{{ $installmentSale->guarantor_mobile }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>NID:</strong>
                            <p>{{ $installmentSale->guarantor_nid ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Address:</strong>
                            <p>{{ $installmentSale->guarantor_address }}</p>
                        </div>
                        @if($installmentSale->guarantor_security_info)
                        <div class="col-12">
                            <strong>Security Information:</strong>
                            <p>{{ $installmentSale->guarantor_security_info }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-box"></i> Products
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($installmentSale->products_data && is_array($installmentSale->products_data))
                                    @foreach($installmentSale->products_data as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product['product_name'] ?? 'N/A' }}</td>
                                        <td>{{ $product['brand'] ?? 'N/A' }}</td>
                                        <td>{{ $product['model'] ?? 'N/A' }}</td>
                                        <td>{{ $product['quantity'] ?? 0 }}</td>
                                        <td>৳{{ number_format($product['unit_price'] ?? 0, 2) }}</td>
                                        <td>৳{{ number_format(($product['quantity'] ?? 0) * ($product['unit_price'] ?? 0), 2) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No products found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="col-md-4">
            <!-- Payment Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave"></i> Payment Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>৳{{ number_format($installmentSale->subtotal, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <strong class="text-danger">৳{{ number_format($installmentSale->discount_amount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span><strong>Total Amount:</strong></span>
                        <strong class="text-primary fs-5">৳{{ number_format($installmentSale->total_amount, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Down Payment:</span>
                        <strong class="text-success">৳{{ number_format($installmentSale->down_payment, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Remaining:</span>
                        <strong class="text-warning">৳{{ number_format($installmentSale->total_amount - $installmentSale->down_payment, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Months:</span>
                        <strong>{{ $installmentSale->total_installments }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Monthly Installment:</span>
                        <strong class="text-info">৳{{ number_format($installmentSale->monthly_installment, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Document Links -->
            @if($installmentSale->customer_nid_image || $installmentSale->customer_wife_nid_image || $installmentSale->guarantor_security_image)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt"></i> Documents
                    </h5>
                </div>
                <div class="card-body">
                    @if($installmentSale->customer_nid_image)
                    <a href="{{ asset('storage/' . $installmentSale->customer_nid_image) }}" 
                       target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="fas fa-id-card"></i> Customer NID
                    </a>
                    @endif
                    @if($installmentSale->customer_wife_nid_image)
                    <a href="{{ asset('storage/' . $installmentSale->customer_wife_nid_image) }}" 
                       target="_blank" class="btn btn-sm btn-outline-info w-100 mb-2">
                        <i class="fas fa-id-card"></i> Spouse NID
                    </a>
                    @endif
                    @if($installmentSale->guarantor_security_image)
                    <a href="{{ asset('storage/' . $installmentSale->guarantor_security_image) }}" 
                       target="_blank" class="btn btn-sm btn-outline-warning w-100 mb-2">
                        <i class="fas fa-shield-alt"></i> Guarantor Security
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
