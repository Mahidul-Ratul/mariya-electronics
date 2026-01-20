@extends('layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your electronics store today.')

@section('content')
<div class="row g-4">
    <!-- Quick Stats -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-primary text-white">
                <i class="fas fa-microchip"></i>
            </div>
            <h3 class="fw-bold mb-1" style="font-size: 2rem;">{{ $totalProducts }}</h3>
            <p class="text-muted mb-0">Total Products</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-arrow-up me-1"></i>+5%
                </span>
                <small class="text-muted ms-2">vs last month</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-success text-white">
                <i class="fas fa-user-friends"></i>
            </div>
            <h3 class="fw-bold mb-1" style="font-size: 2rem;">{{ $totalCustomers }}</h3>
            <p class="text-muted mb-0">Active Customers</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-arrow-up me-1"></i>+12%
                </span>
                <small class="text-muted ms-2">vs last month</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-secondary text-white">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3 class="fw-bold mb-1" style="font-size: 2rem;">{{ $recentSales->count() }}</h3>
            <p class="text-muted mb-0">Total Sales</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-chart-line me-1"></i>Recent
                </span>
                <small class="text-muted ms-2">sales count</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-warning text-white">
                <i class="fas fa-money-bill"></i>
            </div>
            <h3 class="fw-bold mb-1" style="font-size: 2rem;">৳{{ number_format($todaysSales + $todaysPayments, 0) }}</h3>
            <p class="text-muted mb-0">Today's Revenue</p>
            <div class="mt-3 d-flex align-items-center">
                <span class="badge bg-success bg-opacity-10 text-success">
                    <i class="fas fa-calendar me-1"></i>Daily
                </span>
                <small class="text-muted ms-2">sales + payments</small>
            </div>
        </div>
    </div>
</div>
<div class="row g-4 mt-4">
    <!-- Recent Sales -->
    <div class="col-xl-8 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1 fw-bold">Recent Sales</h5>
                    <p class="text-muted mb-0">Latest transactions from your store</p>
                </div>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-primary btn-sm">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentSales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Date</th>
                                    <th>Payment</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                <tr>
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
                                            @if($sale->product)
                                                {{ $sale->product->name }}
                                            @elseif($sale->products_data && is_array($sale->products_data) && count($sale->products_data) > 0)
                                                {{ $sale->products_data[0]['name'] ?? 'Multiple Products' }}
                                            @else
                                                Product N/A
                                            @endif
                                        </div>
                                        <small class="text-muted">Qty: {{ $sale->quantity }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $sale->sale_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $sale->sale_date->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $sale->payment_type === 'cash' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $sale->payment_type === 'cash' ? 'success' : 'warning' }}">
                                            {{ ucfirst($sale->payment_type) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">৳{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i>{{ ucfirst($sale->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-shopping-bag text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                        <h6 class="text-muted">No sales recorded yet</h6>
                        <p class="text-muted mb-3">Start by creating your first sale</p>
                        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Create Sale
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions & Notifications -->
    <div class="col-xl-4 col-lg-5">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-1 fw-bold">Quick Actions</h6>
                <p class="text-muted mb-0">Frequently used operations</p>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('sales.create') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="fas fa-plus me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">New Sale</div>
                            <small class="opacity-75">Process customer transaction</small>
                        </div>
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-microchip me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">Add Product</div>
                            <small class="opacity-75">Expand your inventory</small>
                        </div>
                    </a>
                    <a href="{{ route('customers.create') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-user-plus me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">Add Customer</div>
                            <small class="opacity-75">Register new customer</small>
                        </div>
                    </a>
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                        <i class="fas fa-credit-card me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">Record Payment</div>
                            <small class="opacity-75">Track installments</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-1 fw-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                    </h6>
                    <p class="text-muted mb-0">Products running low</p>
                </div>
                <span class="badge bg-warning bg-opacity-10 text-warning">{{ $lowStockProducts->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($lowStockProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts as $product)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 36px; height: 36px;">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->brand ?? 'No Brand' }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    {{ $product->stock_quantity }} left
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('products.index') }}" class="btn btn-warning btn-sm w-100">
                            <i class="fas fa-boxes me-2"></i>Manage Inventory
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-2">
                            <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h6 class="text-success mb-1">All Stock Levels Good!</h6>
                        <small class="text-muted">Your inventory is well maintained</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Overdue Installments -->
@if($overdueInstallmentsList->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1 fw-bold text-danger">
                        <i class="fas fa-calendar-exclamation me-2"></i>Overdue Installments
                    </h5>
                    <p class="text-muted mb-0">Installments overdue for collection</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger bg-opacity-10 text-danger">
                        {{ $overdueInstallmentsList->count() }} overdue
                    </span>
                    <a href="{{ route('installments.index') }}" class="btn btn-outline-primary btn-sm">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Sale</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Days Overdue</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueInstallmentsList as $installment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-gradient-danger rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium">
                                                {{ $installment->sale->customer ? $installment->sale->customer->name : ($installment->sale->customer_name ?? 'Direct Sale') }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $installment->sale->customer ? $installment->sale->customer->phone : ($installment->sale->customer_mobile ?? 'N/A') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium">Sale #{{ $installment->sale->sale_number }}</div>
                                    <small class="text-muted">
                                        @if($installment->sale->product)
                                            {{ $installment->sale->product->name }}
                                        @elseif($installment->sale->products_data && is_array($installment->sale->products_data) && count($installment->sale->products_data) > 0)
                                            {{ $installment->sale->products_data[0]['name'] ?? 'Multiple Products' }}
                                        @else
                                            Product N/A
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $installment->due_date->format('M d, Y') }}</div>
                                    <small class="text-muted">Installment #{{ $installment->installment_number }}</small>
                                </td>
                                <td class="fw-bold text-primary">৳{{ number_format($installment->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        {{ $installment->days_overdue }} days late
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('installments.show', $installment) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Add some interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Animate counter numbers
        const counters = document.querySelectorAll('.stat-card h3');
        counters.forEach(counter => {
            const target = parseInt(counter.innerText.replace(/[৳,]/g, ''));
            let current = 0;
            const increment = target / 50;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (counter.innerText.includes('৳')) {
                        counter.innerText = '৳' + Math.floor(current).toLocaleString();
                    } else {
                        counter.innerText = Math.floor(current).toLocaleString();
                    }
                    requestAnimationFrame(updateCounter);
                } else {
                    if (counter.innerText.includes('৳')) {
                        counter.innerText = '৳' + target.toLocaleString();
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                }
            };
            
            updateCounter();
        });
    });
</script>
@endsection