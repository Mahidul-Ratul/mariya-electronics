@extends('layout.app')

@section('title', 'Sales Management')
@section('page-title', 'Sales Dashboard')
@section('page-subtitle', 'Manage all your sales transactions, track payments, and generate receipts')

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('sales.choose-type') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Sale
        </a>
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('sales.create-direct') }}">
                <i class="fas fa-money-bill-wave me-2 text-success"></i>Direct Sale
            </a></li>
            <li><a class="dropdown-item" href="{{ route('installment-sales.create') }}">
                <i class="fas fa-calendar-alt me-2 text-info"></i>Installment Sale
            </a></li>
        </ul>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-primary text-white">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3 class="fw-bold mb-1">৳{{ number_format($stats['total_sales'], 0) }}</h3>
            <p class="text-muted mb-0">Total Sales</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-arrow-up me-1"></i>All Time
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-success text-white">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h3 class="fw-bold mb-1">৳{{ number_format($stats['today_sales'], 0) }}</h3>
            <p class="text-muted mb-0">Today's Sales</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-calendar me-1"></i>{{ date('M d, Y') }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-warning text-white">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 class="fw-bold mb-1">৳{{ number_format($stats['cash_sales'], 0) }}</h3>
            <p class="text-muted mb-0">Cash Sales</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-wallet me-1"></i>Cash
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-secondary text-white">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 class="fw-bold mb-1">৳{{ number_format($stats['installment_sales'], 0) }}</h3>
            <p class="text-muted mb-0">Installment Sales</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                    <i class="fas fa-clock me-1"></i>Installments
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('sales.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Search Box -->
                <div class="col-md-3">
                    <label for="search" class="form-label fw-medium mb-1">
                        <i class="fas fa-search me-1"></i>Search
                    </label>
                    <small class="text-muted d-block mb-1">Search by invoice number, customer name, or mobile</small>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Invoice, name, mobile..." value="{{ request('search') }}"
                           autocomplete="off">
                </div>

                <!-- Date Filter -->
                <div class="col-md-2">
                    <label for="date_filter" class="form-label fw-medium mb-2">
                        <i class="fas fa-calendar me-1"></i>Date
                    </label>
                    <select name="date_filter" id="date_filter" class="form-select">
                        <option value="">All Dates</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>

                <!-- Custom Date Range (shown when custom is selected) -->
                <div class="col-md-2" id="dateFromGroup" style="display: {{ request('date_filter') === 'custom' ? 'block' : 'none' }};">
                    <label for="date_from" class="form-label fw-medium mb-2">From</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2" id="dateToGroup" style="display: {{ request('date_filter') === 'custom' ? 'block' : 'none' }};">
                    <label for="date_to" class="form-label fw-medium mb-2">To</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <!-- Payment Type -->
                <div class="col-md-2">
                    <label for="payment_type" class="form-label fw-medium mb-2">
                        <i class="fas fa-wallet me-1"></i>Payment
                    </label>
                    <select name="payment_type" id="payment_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="cash" {{ request('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bkash" {{ request('payment_type') == 'bkash' ? 'selected' : '' }}>Bkash</option>
                        <option value="nagad" {{ request('payment_type') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="bank" {{ request('payment_type') == 'bank' ? 'selected' : '' }}>Bank</option>
                        <option value="installment" {{ request('payment_type') == 'installment' ? 'selected' : '' }}>Installment</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-2">
                    <label for="status" class="form-label fw-medium mb-2">
                        <i class="fas fa-flag me-1"></i>Status
                    </label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="due" {{ request('status') == 'due' ? 'selected' : '' }}>Due</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let isLoading = false;
let abortController = null;

// Make deleteSale globally accessible
window.deleteSale = function(saleId, saleNumber) {
    if (confirm(`Are you sure you want to delete sale ${saleNumber}?\n\nThis action cannot be undone and will restore the product stock.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/sales/${saleId}`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Handle custom date range visibility
    const dateFilter = document.getElementById('date_filter');
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            const customSelected = this.value === 'custom';
            document.getElementById('dateFromGroup').style.display = customSelected ? 'block' : 'none';
            document.getElementById('dateToGroup').style.display = customSelected ? 'block' : 'none';
        });
    }
    
    initializeTooltips();
});

// Apply filters with AJAX
function applyFilters() {
    console.log('applyFilters() called');
    
    // Abort previous request if still loading
    if (isLoading && abortController) {
        console.log('Aborting previous request');
        abortController.abort();
    }
    
    // Create new abort controller for this request
    abortController = new AbortController();
    
    const form = document.getElementById('filterForm');
    if (!form) {
        console.error('filterForm not found!');
        return;
    }
    
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Build clean params (skip empty values)
    for (let [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            params.append(key, value);
            console.log('Param:', key, '=', value);
        }
    }
    
    // Show loading state
    isLoading = true;
    const loadingBtn = document.getElementById('loadingBtn');
    if (loadingBtn) {
        loadingBtn.style.display = 'inline-block';
    }
    
    // Update URL
    const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
    console.log('Fetching:', newUrl);
    window.history.pushState({}, '', newUrl);
    
    // Fetch filtered results
    fetch(newUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        },
        signal: abortController.signal
    })
    .then(response => {
        console.log('Response received:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        console.log('Parsing HTML...');
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update table
        const newTableContainer = doc.querySelector('.table-responsive');
        const currentTableContainer = document.querySelector('.table-responsive');
        
        if (newTableContainer && currentTableContainer) {
            currentTableContainer.innerHTML = newTableContainer.innerHTML;
            console.log('Table updated');
        } else {
            console.error('Table container not found');
        }
        
        // Update pagination
        const newPagination = doc.querySelector('.card-footer');
        const currentPagination = document.querySelector('.card-footer');
        
        if (newPagination && currentPagination) {
            currentPagination.innerHTML = newPagination.innerHTML;
            console.log('Pagination updated');
        }
        
        // Reinitialize tooltips
        initializeTooltips();
        console.log('Filters applied successfully');
    })
    .catch(error => {
        // Only handle non-abort errors
        if (error.name !== 'AbortError') {
            console.error('AJAX Error:', error);
            // On error, do a full page reload
            window.location.href = newUrl;
        } else {
            console.log('Request aborted');
        }
    })
    .finally(() => {
        isLoading = false;
        abortController = null;
        if (loadingBtn) {
            loadingBtn.style.display = 'none';
        }
        console.log('Request completed');
    });
}

function initializeTooltips() {
    // Dispose existing tooltips first
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        const tooltip = bootstrap.Tooltip.getInstance(el);
        if (tooltip) tooltip.dispose();
    });
    
    // Initialize new tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}
</script>

<!-- Sales Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-1 fw-bold">Sales Transactions</h5>
        <p class="text-muted mb-0">Manage and track all sales activities</p>
    </div>
    
    @if($sales->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Sale #</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Date</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">#{{ $sale->sale_number }}</div>
                            <small class="text-muted">{{ $sale->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-gradient-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">
                                        {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Direct Sale') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? 'N/A') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium">
                                @php
                                    $productsList = $sale->products_list ?? [];
                                    $productsCount = count($productsList);
                                @endphp
                                
                                @if($productsCount > 1)
                                    <span class="text-primary">{{ $productsCount }} Products</span>
                                    <div class="small text-muted mt-1">
                                        @foreach(array_slice($productsList, 0, 2) as $product)
                                            • {{ $product['product_name'] ?? 'Product' }}<br>
                                        @endforeach
                                        @if($productsCount > 2)
                                            <span class="text-muted fst-italic">+ {{ $productsCount - 2 }} more...</span>
                                        @endif
                                    </div>
                                @elseif($productsCount === 1)
                                    {{ $productsList[0]['product_name'] ?? 'Product' }}
                                    <small class="text-muted d-block">
                                        @if(isset($productsList[0]['brand']))
                                            {{ $productsList[0]['brand'] }}
                                        @endif
                                        @if(isset($productsList[0]['model']))
                                            - {{ $productsList[0]['model'] }}
                                        @endif
                                    </small>
                                @elseif($sale->product)
                                    {{ $sale->product->name }}
                                @else
                                    Product N/A
                                @endif
                            </div>
                            <small class="text-muted">
                                @if($productsCount > 1)
                                    Total Qty: {{ $sale->total_quantity }}
                                @else
                                    Qty: {{ $sale->quantity ?? $sale->total_quantity }} × ৳{{ number_format($sale->unit_price ?? 0, 2) }}
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $sale->sale_date->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $sale->sale_date->diffForHumans() }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $sale->payment_type === 'cash' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $sale->payment_type === 'cash' ? 'success' : 'warning' }}">
                                <i class="fas fa-{{ $sale->payment_type === 'cash' ? 'money-bill' : 'calendar-check' }} me-1"></i>
                                {{ ucfirst($sale->payment_type) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-success">৳{{ number_format($sale->total_amount, 2) }}</div>
                            @if($sale->discount_percentage > 0)
                                <small class="text-muted">{{ $sale->discount_percentage }}% discount</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                {{ $sale->status === 'completed' ? 'bg-success' : '' }}
                                {{ $sale->status === 'pending' ? 'bg-warning' : '' }}
                                {{ $sale->status === 'cancelled' ? 'bg-danger' : '' }} 
                                bg-opacity-10 text-{{ $sale->status === 'completed' ? 'success' : ($sale->status === 'pending' ? 'warning' : 'danger') }}">
                                <i class="fas fa-{{ $sale->status === 'completed' ? 'check-circle' : ($sale->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('sales.show', $sale) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="View Details"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sales.edit', $sale) }}" 
                                   class="btn btn-sm btn-outline-warning" 
                                   title="Edit Sale"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Delete Sale"
                                        data-bs-toggle="tooltip"
                                        onclick="deleteSale({{ $sale->id }}, '{{ $sale->sale_number }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($sales->hasPages())
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} results
                    </div>
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted mb-3">No Sales Found</h5>
            <p class="text-muted mb-4">You haven't recorded any sales yet, or no sales match your current filters.</p>
            <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Sale
                </a>
                @if(request()->hasAny(['date_filter', 'payment_type', 'status', 'search']))
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeTooltips();
    });

    // Delete sale function
    function deleteSale(saleId, saleNumber) {
        if (confirm(`Are you sure you want to delete sale ${saleNumber}?\n\nThis action cannot be undone and will restore the product stock.`)) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/sales/${saleId}`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush