@extends('layout.app')

@section('title', 'Sales')
@section('page-title', 'Sales Management')

@section('header-actions')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Sale
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($stats['total_sales'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($stats['today_sales'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cash Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($stats['cash_sales'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Installment Sales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($stats['installment_sales'], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('sales.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="date_filter" class="form-select">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="payment_type" class="form-select">
                            <option value="">All Payment Types</option>
                            <option value="cash" {{ request('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="installment" {{ request('payment_type') == 'installment' ? 'selected' : '' }}>Installment</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sales Records ({{ $sales->total() }})</h5>
            </div>
            <div class="card-body">
                @if($sales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sale #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Payment Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td>
                                        <strong>{{ $sale->sale_number }}</strong>
                                    </td>
                                    <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>
                                            {{ $sale->customer ? $sale->customer->name : ($sale->customer_name ?? 'Direct Sale') }}
                                        </strong>
                                        <br><small class="text-muted">
                                            {{ $sale->customer ? $sale->customer->phone : ($sale->customer_mobile ?? 'N/A') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($sale->product)
                                            {{ $sale->product->name }}
                                            @if($sale->product->brand)
                                                <br><small class="text-muted">{{ $sale->product->brand }}</small>
                                            @endif
                                        @elseif($sale->products_data && is_array($sale->products_data) && count($sale->products_data) > 0)
                                            {{ $sale->products_data[0]['name'] ?? 'Multiple Products' }}
                                        @else
                                            Product N/A
                                        @endif
                                    </td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>৳{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        @if($sale->payment_type === 'cash')
                                            <span class="badge bg-success">Cash</span>
                                        @else
                                            <span class="badge bg-warning">Installment</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($sale->status)
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sales.receipt', $sale) }}" class="btn btn-outline-success" title="Receipt">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Delete" onclick="confirmDelete({{ $sale->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $sales->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No sales found</h5>
                        <p class="text-muted">Start by creating your first sale.</p>
                        <a href="{{ route('sales.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Sale
                        </a>
                    </div>
                @endif
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
                Are you sure you want to delete this sale? This will also restore the product stock.
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
function confirmDelete(saleId) {
    document.getElementById('deleteForm').action = '/sales/' + saleId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection