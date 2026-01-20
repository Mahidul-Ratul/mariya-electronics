@extends('layout.app')

@section('title', 'Installment Sales Management')
@section('page-title', 'Installment Sales Dashboard')
@section('page-subtitle', 'Manage installment sales, track payments, and generate invoices')

@section('header-actions')
    <a href="{{ route('installment-sales.create') }}" class="btn btn-info">
        <i class="fas fa-plus me-2"></i>New Installment Sale
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-info text-white">
                <i class="fas fa-receipt"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $stats['total_count'] }}</h3>
            <p class="text-muted mb-0">Total Installment Sales</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-success text-white">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3 class="fw-bold mb-1">৳{{ number_format($stats['total_sales'], 0) }}</h3>
            <p class="text-muted mb-0">Total Sales Amount</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-warning text-white">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $stats['active_count'] }}</h3>
            <p class="text-muted mb-0">Active Installments</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-danger text-white">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $stats['overdue_count'] }}</h3>
            <p class="text-muted mb-0">Overdue Payments</p>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="{{ route('installment-sales.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <!-- Search Box -->
                <div class="col-md-3">
                    <label for="search" class="form-label fw-medium mb-1">
                        <i class="fas fa-search me-1"></i>Search
                    </label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Invoice, customer name, mobile..." 
                           value="{{ request('search') }}">
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label for="payment_status" class="form-label fw-medium mb-1">
                        <i class="fas fa-filter me-1"></i>Payment Status
                    </label>
                    <select class="form-select" id="payment_status" name="payment_status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('payment_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="defaulted" {{ request('payment_status') == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                        <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-md-2">
                    <label for="date_from" class="form-label fw-medium mb-1">
                        <i class="fas fa-calendar me-1"></i>From Date
                    </label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label fw-medium mb-1">
                        <i class="fas fa-calendar me-1"></i>To Date
                    </label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>

                <!-- Action Buttons -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('installment-sales.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sales Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Total Amount</th>
                        <th>Down Payment</th>
                        <th>Monthly</th>
                        <th>Months</th>
                        <th>Sale Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($installmentSales as $sale)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $sale->installment_sale_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $sale->customer_name }}</strong>
                                <br><small class="text-muted">{{ $sale->customer_mobile }}</small>
                            </div>
                        </td>
                        <td>
                            @if($sale->products_data && is_array($sale->products_data))
                                <small>{{ count($sale->products_data) }} item(s)</small>
                            @else
                                <small class="text-muted">N/A</small>
                            @endif
                        </td>
                        <td><strong>৳{{ number_format($sale->total_amount, 2) }}</strong></td>
                        <td>৳{{ number_format($sale->down_payment, 2) }}</td>
                        <td class="text-info">৳{{ number_format($sale->monthly_installment, 2) }}</td>
                        <td>{{ $sale->total_installments }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                        <td>
                            @if($sale->payment_status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($sale->payment_status == 'completed')
                                <span class="badge bg-primary">Completed</span>
                            @elseif($sale->payment_status == 'defaulted')
                                <span class="badge bg-danger">Defaulted</span>
                            @elseif($sale->payment_status == 'cancelled')
                                <span class="badge bg-secondary">Cancelled</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('installment-sales.show', $sale->id) }}" 
                                   class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('installment-sales.invoice', $sale->id) }}" 
                                   class="btn btn-sm btn-outline-success" title="Invoice" target="_blank">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <a href="{{ route('installment-sales.edit', $sale->id) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="deleteSale({{ $sale->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No installment sales found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                Showing {{ $installmentSales->firstItem() ?? 0 }} to {{ $installmentSales->lastItem() ?? 0 }} 
                of {{ $installmentSales->total() }} results
            </div>
            <div>
                {{ $installmentSales->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
function deleteSale(id) {
    if (confirm('Are you sure you want to delete this installment sale?')) {
        const form = document.getElementById('delete-form');
        form.action = `/installment-sales/${id}`;
        form.submit();
    }
}
</script>
@endsection
