@extends('layout.app')

@section('title', 'Invoice - ' . $sale->sale_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Invoice Print/Download Actions -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">Invoice {{ $sale->sale_number }}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('sales.invoice.download', $sale) }}" class="btn btn-success me-2">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Content -->
            <div class="card">
                <div class="card-body">
                    <!-- Company Header -->
                    <div class="text-center mb-4 border-bottom pb-3">
                        <h2 class="mb-1">MARIYA ELECTRONICS</h2>
                        <p class="text-muted mb-0">Electronics & Mobile Shop</p>
                    </div>

                    <!-- Invoice Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Customer Details:</h6>
                            <p class="mb-1"><strong>{{ $sale->customer->name ?? 'Walk-in Customer' }}</strong></p>
                            @if($sale->customer && $sale->customer->phone)
                                <p class="mb-1">{{ $sale->customer->phone }}</p>
                            @endif
                            @if($sale->customer && $sale->customer->address)
                                <p class="mb-1">{{ $sale->customer->address }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>Invoice Details:</h6>
                            <p class="mb-1"><strong>Invoice: {{ $sale->sale_number }}</strong></p>
                            <p class="mb-1">Date: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-M-Y') }}</p>
                            <p class="mb-1">Sale Type: {{ ucfirst($sale->sale_type) }}</p>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
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
                                    <td>৳ {{ number_format((float)$sale->unit_price, 2) }}</td>
                                    <td>৳ {{ number_format((float)$sale->unit_price * (int)($sale->quantity ?? 1), 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">৳ {{ number_format($sale->subtotal ?? ((float)$sale->unit_price * (int)($sale->quantity ?? 1)), 2) }}</td>
                                </tr>
                                @if($sale->discount_amount > 0)
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td class="text-end">-৳ {{ number_format((float)$sale->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>৳ {{ number_format((float)$sale->total_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Paid:</strong></td>
                                    <td class="text-end">৳ {{ number_format((float)$sale->paid_amount, 2) }}</td>
                                </tr>
                                @if($sale->total_amount - $sale->paid_amount > 0)
                                <tr>
                                    <td><strong>Due:</strong></td>
                                    <td class="text-end text-danger"><strong>৳ {{ number_format((float)$sale->total_amount - (float)$sale->paid_amount, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted mb-0">Thank you for your business!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection