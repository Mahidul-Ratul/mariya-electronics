@extends('layout.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('header-actions')
    <div>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Product
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $product->name }}</h5>
                <div>
                    @if($product->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($product->image)
                    <div class="mb-3 text-center">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Brand:</strong> {{ $product->brand ?: 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Model:</strong> {{ $product->model ?: 'N/A' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Category:</strong> 
                        <span class="badge bg-info">{{ $product->category }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Warranty:</strong> {{ $product->warranty_period ?: 'N/A' }}
                    </div>
                </div>

                @if($product->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Cost Price</h6>
                                <h4 class="text-danger">৳{{ number_format($product->cost_price, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Selling Price</h6>
                                <h4 class="text-success">৳{{ number_format($product->selling_price, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Max Discount</h6>
                                <h4 class="text-warning">{{ $product->max_discount }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title">Stock</h6>
                                <h4 class="{{ $product->stock_quantity <= 0 ? 'text-danger' : ($product->stock_quantity <= 10 ? 'text-warning' : 'text-success') }}">
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($salesHistory->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Recent Sales History</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesHistory as $sale)
                            <tr>
                                <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                <td>{{ $sale->customer->name }}</td>
                                <td>{{ $sale->quantity }}</td>
                                <td>৳{{ number_format($sale->unit_price, 2) }}</td>
                                <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="updateStock()">
                        <i class="fas fa-boxes"></i> Update Stock
                    </button>
                    <a href="{{ route('sales.create', ['product' => $product->id]) }}" class="btn btn-outline-success">
                        <i class="fas fa-shopping-cart"></i> Create Sale
                    </a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Edit Product
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Product Statistics</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Total Sales:</small>
                    <div class="fw-bold">{{ $salesHistory->sum('quantity') }} {{ $product->unit }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Revenue Generated:</small>
                    <div class="fw-bold">৳{{ number_format($salesHistory->sum('total_amount'), 2) }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Added:</small>
                    <div class="fw-bold">{{ $product->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="updateStockForm">
                    <div class="mb-3">
                        <label for="new_stock" class="form-label">New Stock Quantity</label>
                        <input type="number" class="form-control" id="new_stock" value="{{ $product->stock_quantity }}" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStock()">Update Stock</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateStock() {
    new bootstrap.Modal(document.getElementById('updateStockModal')).show();
}

function saveStock() {
    const newStock = document.getElementById('new_stock').value;
    
    fetch(`/products/{{ $product->id }}/stock`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            stock_quantity: newStock
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection