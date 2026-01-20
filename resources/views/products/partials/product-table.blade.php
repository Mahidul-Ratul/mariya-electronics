@if($products->count() > 0)
    <!-- Desktop Table View -->
    <div class="d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Brand/Model</th>
                        <th>Category</th>
                        <th>Cost Price</th>
                        <th>Selling Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 4px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                    </td>
                    <td>
                        @if($product->brand)
                            {{ $product->brand }}
                            @if($product->model)
                                <br><small class="text-muted">{{ $product->model }}</small>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $product->category }}</span>
                    </td>
                    <td>৳{{ number_format($product->cost_price, 2) }}</td>
                    <td>৳{{ number_format($product->selling_price, 2) }}</td>
                    <td>
                        <span class="badge {{ $product->stock_quantity <= 0 ? 'bg-danger' : ($product->stock_quantity <= 10 ? 'bg-warning' : 'bg-success') }}">
                            {{ $product->stock_quantity }} {{ $product->unit }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete({{ $product->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="d-lg-none">
        <div class="row g-3">
            @foreach($products as $product)
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            @if($product->image)
                                <div class="me-3">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-0 fw-bold">{{ $product->name }}</h6>
                            </div>
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="row text-sm mb-3">
                            <div class="col-6">
                                <strong>Brand/Model:</strong><br>
                                <span class="text-muted">
                                    {{ $product->brand ?? 'N/A' }}
                                    @if($product->model) / {{ $product->model }} @endif
                                </span>
                            </div>
                            <div class="col-6">
                                <strong>Category:</strong><br>
                                <span class="text-muted">{{ $product->category ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="row text-sm mb-3">
                            <div class="col-4">
                                <strong>Cost:</strong><br>
                                <span class="text-muted">৳{{ number_format($product->cost_price, 2) }}</span>
                            </div>
                            <div class="col-4">
                                <strong>Selling:</strong><br>
                                <span class="text-success fw-bold">৳{{ number_format($product->selling_price, 2) }}</span>
                            </div>
                            <div class="col-4">
                                <strong>Stock:</strong><br>
                                <span class="badge {{ $product->stock_quantity > 10 ? 'bg-success' : ($product->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info flex-fill">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="confirmDelete({{ $product->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No products found</h5>
        <p class="text-muted">Try adjusting your search criteria or add a new product.</p>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
@endif