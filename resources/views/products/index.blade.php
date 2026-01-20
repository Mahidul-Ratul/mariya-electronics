@extends('layout.app')

@section('title', 'Products')
@section('page-title', 'Product Management')

@section('header-actions')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Product
    </a>
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}" class="row g-3" id="searchForm">
                    <div class="col-lg-3 col-md-6 col-12 position-relative">
                        <input type="text" class="form-control" name="search" id="searchInput" placeholder="Search products..." 
                               value="{{ request('search') }}">
                        <div id="searchSpinner" class="position-absolute top-50 end-0 translate-middle-y me-3" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <select name="category_id" id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <select name="brand_id" id="brandFilter" class="form-select">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <select name="stock_status" id="stockFilter" class="form-select">
                            <option value="">All Stock Status</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Products <span id="productCount">({{ $products->total() }})</span></h5>
                <div>
                    <a href="{{ route('products.low-stock') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-exclamation-triangle"></i> Low Stock
                    </a>
                </div>
            </div>
            <div class="card-body" id="productResults">
                @include('products.partials.product-table', ['products' => $products])
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(productId) {
    document.getElementById('deleteForm').action = '/products/' + productId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Modern AJAX Search Implementation
let searchTimeout;

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const brandFilter = document.getElementById('brandFilter');
    const stockFilter = document.getElementById('stockFilter');
    const productResults = document.getElementById('productResults');
    const productCount = document.getElementById('productCount');
    const searchSpinner = document.getElementById('searchSpinner');

    // Debounced search function
    function performSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            const formData = new URLSearchParams();
            
            // Collect search parameters
            if (searchInput.value.trim()) {
                formData.append('search', searchInput.value.trim());
            }
            if (categoryFilter.value) {
                formData.append('category_id', categoryFilter.value);
            }
            if (brandFilter.value) {
                formData.append('brand_id', brandFilter.value);
            }
            if (stockFilter.value) {
                formData.append('stock_status', stockFilter.value);
            }

            // Show loading spinner
            searchSpinner.style.display = 'block';

            // Perform AJAX request
            fetch(`{{ route('products.search') }}?${formData.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update results
                productResults.innerHTML = html;
                
                // Update URL without page refresh
                const newUrl = `{{ route('products.index') }}?${formData.toString()}`;
                window.history.pushState({}, '', newUrl);
                
                // Update product count
                const countMatch = html.match(/No products found|(\d+)/);
                if (html.includes('No products found')) {
                    productCount.textContent = '(0)';
                } else {
                    // Extract count from pagination or table rows
                    const rows = (html.match(/class="table table-striped"/g) || []).length;
                    if (rows > 0) {
                        const tableRows = html.split('<tr>').length - 1;
                        const actualRows = Math.max(0, tableRows - 1); // Subtract header row
                        productCount.textContent = `(${actualRows})`;
                    }
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                productResults.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                        <h5 class="text-muted">Search Error</h5>
                        <p class="text-muted">Please try again in a moment.</p>
                    </div>
                `;
            })
            .finally(() => {
                // Hide loading spinner
                searchSpinner.style.display = 'none';
            });
        }, 300); // 300ms delay for search input
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    categoryFilter.addEventListener('change', performSearch);
    brandFilter.addEventListener('change', performSearch);
    stockFilter.addEventListener('change', performSearch);

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        location.reload();
    });
});
</script>
@endsection