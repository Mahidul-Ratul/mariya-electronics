@extends('layout.app')

@section('title', 'Choose Sale Type')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="fas fa-cash-register me-2"></i>
                        Choose Sale Type
                    </h2>
                </div>
                <div class="card-body p-5">
                    <div class="row g-4">
                        <!-- Direct Sale Option -->
                        <div class="col-md-6">
                            <div class="card h-100 border-success sale-option-card">
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="mb-4">
                                        <i class="fas fa-money-bill-wave text-success" style="font-size: 3rem;"></i>
                                    </div>
                                    <h4 class="card-title text-success mb-3">Direct Sale</h4>
                                    <p class="card-text flex-grow-1">
                                        Complete cash sale with immediate payment. 
                                        Perfect for walk-in customers who pay the full amount upfront.
                                    </p>
                                    <ul class="list-unstyled text-muted mb-4">
                                        <li><i class="fas fa-check text-success me-2"></i>Immediate payment</li>
                                        <li><i class="fas fa-check text-success me-2"></i>No installments</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Quick processing</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Instant receipt</li>
                                    </ul>
                                    <a href="{{ route('sales.create-direct') }}" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-plus me-2"></i>Start Direct Sale
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Installment Sale Option -->
                        <div class="col-md-6">
                            <div class="card h-100 border-info sale-option-card">
                                <div class="card-body text-center d-flex flex-column">
                                    <div class="mb-4">
                                        <i class="fas fa-calendar-alt text-info" style="font-size: 3rem;"></i>
                                    </div>
                                    <h4 class="card-title text-info mb-3">Installment Sale</h4>
                                    <p class="card-text flex-grow-1">
                                        Sales with monthly payments. 
                                        Ideal for high-value items with customer payment plans.
                                    </p>
                                    <ul class="list-unstyled text-muted mb-4">
                                        <li><i class="fas fa-check text-info me-2"></i>Monthly payments</li>
                                        <li><i class="fas fa-check text-info me-2"></i>Down payment option</li>
                                        <li><i class="fas fa-check text-info me-2"></i>Payment tracking</li>
                                        <li><i class="fas fa-check text-info me-2"></i>Customer management</li>
                                    </ul>
                                    <a href="{{ route('installment-sales.create') }}" class="btn btn-info btn-lg w-100">
                                        <i class="fas fa-plus me-2"></i>Start Installment Sale
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            <i class="fas fa-info-circle text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <div class="col-md-10">
                                            <h5 class="card-title mb-2">Quick Help</h5>
                                            <p class="card-text mb-0">
                                                <strong>Direct Sale:</strong> Choose this for immediate cash payments or when the customer pays the full amount at once.<br>
                                                <strong>Installment Sale:</strong> Choose this when the customer wants to pay in monthly installments over time.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Sales List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sale-option-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.sale-option-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.sale-option-card .card-body {
    padding: 2rem;
}

.sale-option-card .btn {
    transition: all 0.3s ease;
}

.sale-option-card:hover .btn {
    transform: scale(1.05);
}
</style>
@endsection