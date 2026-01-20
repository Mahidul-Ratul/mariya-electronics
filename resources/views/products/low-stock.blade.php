@extends('layout.app')

@section('title', 'Low Stock Products')
@section('page-title', 'Low Stock Alert')

@section('header-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        @if($products->count() > 0)
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Alert!</strong> {{ $products->count() }} products are running low on stock (≤10 items).
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Products with Low Stock</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr class="{{ $product->stock_quantity == 0 ? 'table-danger' : 'table-warning' }}">
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        @if($product->brand)
                                            <br><small class="text-muted">{{ $product->brand }} {{ $product->model }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $product->category }}</td>
                                    <td>
                                        <span class="badge {{ $product->stock_quantity == 0 ? 'bg-danger' : 'bg-warning' }}">
                                            {{ $product->stock_quantity }} {{ $product->unit }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->stock_quantity == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @else
                                            <span class="badge bg-warning">Low Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity }})">
                                                <i class="fas fa-boxes"></i> Update Stock
                                            </button>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4 class="text-success">All Products Well Stocked!</h4>
                    <p class="text-muted">No products are currently running low on stock.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-box"></i> View All Products
                    </a>
                </div>
            </div>
        @endif
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
                        <label for="product_name" class="form-label">Product</label>
                        <input type="text" class="form-control" id="product_name" readonly>
                        <input type="hidden" id="product_id">
                    </div>
                    <div class="mb-3">
                        <label for="current_stock" class="form-label">Current Stock</label>
                        <input type="number" class="form-control" id="current_stock" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="new_stock" class="form-label">New Stock Quantity</label>
                        <input type="number" class="form-control" id="new_stock" min="0" required>
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
function updateStock(productId, currentStock) {
    document.getElementById('product_id').value = productId;
    document.getElementById('current_stock').value = currentStock;
    document.getElementById('new_stock').value = currentStock;
    
    // Get product name (you might want to pass this as well)
    const productName = event.target.closest('tr').querySelector('strong').textContent;
    document.getElementById('product_name').value = productName;
    
    new bootstrap.Modal(document.getElementById('updateStockModal')).show();
}

function saveStock() {
    const productId = document.getElementById('product_id').value;
    const newStock = document.getElementById('new_stock').value;
    
    fetch(`/products/${productId}/stock`, {
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
        } else {
            alert('Error updating stock');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating stock');
    });
}
</script>
@endsection