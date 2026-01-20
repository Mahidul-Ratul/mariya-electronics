@extends('layout.app')

@section('title', 'Payments')
@section('page-title', 'Payment Records')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's Collections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($totalToday, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">This Month's Collections</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">৳{{ number_format($totalThisMonth, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                <form method="GET" action="{{ route('payments.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search customer..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="date_filter" class="form-select">
                            <option value="">Date Filter</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="payment_method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}" placeholder="End Date">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Records ({{ $payments->total() }})</h5>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>{{ $payment->sale->customer->name }}</strong>
                                        <br><small class="text-muted">{{ $payment->sale->customer->phone }}</small>
                                    </td>
                                    <td>
                                        {{ $payment->sale->product->name }}
                                        <br><small class="text-muted">Sale #{{ $payment->sale->sale_number }}</small>
                                    </td>
                                    <td>৳{{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                    </td>
                                    <td>{{ $payment->reference_number ?: 'N/A' }}</td>
                                    <td>
                                        @if($payment->installment_id)
                                            <span class="badge bg-warning">Installment #{{ $payment->installment->installment_number }}</span>
                                        @else
                                            <span class="badge bg-success">Cash Sale</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="3"><strong>Total on this page:</strong></td>
                                    <td><strong>৳{{ number_format($payments->sum('amount'), 2) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $payments->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No payments found</h5>
                        <p class="text-muted">Payment records will appear here when customers make payments.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection