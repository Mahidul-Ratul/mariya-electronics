@extends('layout.app')

@section('title', 'New Sale')
@section('page-title', 'Create New Sale')

@section('header-actions')
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
        <i class="fas fa-arrow-left me-2"></i> Back to Sales
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle me-3 d-flex align-items-center justify-content-center" 
                     style="width: 40px; height: 40px;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Sale Information</h5>
                    <small class="opacity-75">Complete transaction details</small>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('sales.store') }}" id="saleForm">
                    @csrf
                    
                    <!-- Customer & Product Selection -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('customer_id') is-invalid @enderror" 
                                        id="customer_id" name="customer_id" required>
                                    <option value="">Choose customer...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="customer_id" class="form-label">
                                    <i class="fas fa-user me-2 text-primary"></i>Customer *
                                </label>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('customers.create') }}" target="_blank" 
                                   class="btn btn-link btn-sm p-0 text-decoration-none">
                                    <i class="fas fa-plus-circle me-1"></i>Add new customer
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('product_id') is-invalid @enderror" 
                                        id="product_id" name="product_id" required onchange="updateProductInfo()">
                                    <option value="">Choose product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->selling_price }}"
                                                data-stock="{{ $product->stock_quantity }}"
                                                data-max-discount="{{ $product->max_discount }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} - ৳{{ number_format($product->selling_price, 2) }}
                                            (Stock: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                <label for="product_id" class="form-label">
                                    <i class="fas fa-microchip me-2 text-success"></i>Product *
                                </label>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quantity, Price & Discount -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                                       required onchange="calculateTotal()" placeholder="1">
                                <label for="quantity" class="form-label">
                                    <i class="fas fa-layer-group me-2 text-info"></i>Quantity *
                                </label>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small id="stockInfo" class="form-text text-muted mt-1"></small>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" 
                                       id="unit_price" name="unit_price" value="{{ old('unit_price') }}" 
                                       required onchange="calculateTotal()" placeholder="0.00">
                                <label for="unit_price" class="form-label">
                                    <i class="fas fa-money-bill me-2 text-warning"></i>Unit Price (৳) *
                                </label>
                                @error('unit_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" step="0.01" max="100" class="form-control @error('discount_percentage') is-invalid @enderror" 
                                       id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', 0) }}" 
                                       onchange="calculateTotal()" placeholder="0">
                                <label for="discount_percentage" class="form-label">
                                    <i class="fas fa-percentage me-2 text-danger"></i>Discount (%)
                                </label>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small id="maxDiscountInfo" class="form-text text-muted mt-1"></small>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="date" class="form-control @error('sale_date') is-invalid @enderror" 
                                       id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" 
                                       required placeholder="Date">
                                <label for="sale_date" class="form-label">
                                    <i class="fas fa-calendar me-2 text-secondary"></i>Sale Date *
                                </label>
                                @error('sale_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('payment_type') is-invalid @enderror" 
                                        id="payment_type" name="payment_type" required onchange="toggleInstallmentOptions()">
                                    <option value="">Choose payment method...</option>
                                    <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash Payment</option>
                                    <option value="installment" {{ old('payment_type') == 'installment' ? 'selected' : '' }}>Installment Payment</option>
                                </select>
                                <label for="payment_type" class="form-label">
                                    <i class="fas fa-credit-card me-2 text-primary"></i>Payment Type *
                                </label>
                                @error('payment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6" id="installmentOptions" style="display: none;">
                            <div class="form-floating">
                                <select class="form-select @error('installment_months') is-invalid @enderror" 
                                        id="installment_months" name="installment_months">
                                    <option value="">Select duration...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('installment_months') == $i ? 'selected' : '' }}>{{ $i }} Month(s)</option>
                                    @endfor
                                </select>
                                <label for="installment_months" class="form-label">
                                    <i class="fas fa-calendar-alt me-2 text-info"></i>Number of Months
                                </label>
                                @error('installment_months')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <div class="form-floating">
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" style="height: 100px" 
                                      placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-2 text-secondary"></i>Notes (Optional)
                            </label>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary d-flex align-items-center" onclick="history.back()">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-gradient-primary btn-lg d-flex align-items-center">
                            <i class="fas fa-save me-2"></i>Complete Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Sale Summary -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient-success text-white d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle me-3 d-flex align-items-center justify-content-center" 
                     style="width: 36px; height: 36px;">
                    <i class="fas fa-calculator"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Sale Summary</h6>
                    <small class="opacity-75">Transaction breakdown</small>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-bold h6 mb-0" id="subtotalDisplay">৳0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Discount:</span>
                    <span class="fw-bold h6 mb-0 text-danger" id="discountDisplay">৳0.00</span>
                </div>
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Amount:</span>
                    <span class="fw-bold h4 mb-0 text-success" id="totalDisplay">৳0.00</span>
                </div>
                
                <div id="installmentSummary" style="display: none;" class="border-top pt-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-info me-2"></i>
                        <h6 class="mb-0 fw-bold">Installment Details</h6>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Monthly Payment:</span>
                        <span class="fw-bold h6 mb-0 text-primary" id="monthlyPaymentDisplay">৳0.00</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-info text-white d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle me-3 d-flex align-items-center justify-content-center" 
                     style="width: 36px; height: 36px;">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Quick Actions</h6>
                    <small class="opacity-75">Shortcuts to common tasks</small>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="{{ route('customers.create') }}" class="btn btn-outline-primary d-flex align-items-center" target="_blank">
                        <i class="fas fa-user-plus me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">Add Customer</div>
                            <small class="opacity-75">Register new customer</small>
                        </div>
                    </a>
                    <a href="{{ route('products.create') }}" class="btn btn-outline-success d-flex align-items-center" target="_blank">
                        <i class="fas fa-microchip me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">Add Product</div>
                            <small class="opacity-75">Expand inventory</small>
                        </div>
                    </a>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-list me-3"></i>
                        <div class="text-start">
                            <div class="fw-bold">View All Sales</div>
                            <small class="opacity-75">Browse transactions</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    color: white;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: #667eea;
    transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
}

.card {
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-floating > .form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-secondary:hover {
    transform: translateY(-1px);
}
</style>

<script>
function updateProductInfo() {
    const select = document.getElementById('product_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        const stock = selectedOption.getAttribute('data-stock');
        const maxDiscount = selectedOption.getAttribute('data-max-discount');
        
        document.getElementById('unit_price').value = price;
        document.getElementById('stockInfo').innerHTML = `<i class="fas fa-boxes text-info me-1"></i>Available: <strong>${stock}</strong> units`;
        document.getElementById('maxDiscountInfo').innerHTML = `<i class="fas fa-percentage text-danger me-1"></i>Max discount: <strong>${maxDiscount}%</strong>`;
        document.getElementById('discount_percentage').max = maxDiscount;
        
        // Update quantity max
        document.getElementById('quantity').max = stock;
        
        calculateTotal();
    } else {
        document.getElementById('unit_price').value = '';
        document.getElementById('stockInfo').innerHTML = '';
        document.getElementById('maxDiscountInfo').innerHTML = '';
    }
}

function toggleInstallmentOptions() {
    const paymentType = document.getElementById('payment_type').value;
    const installmentOptions = document.getElementById('installmentOptions');
    const installmentSummary = document.getElementById('installmentSummary');
    
    if (paymentType === 'installment') {
        installmentOptions.style.display = 'block';
        installmentSummary.style.display = 'block';
        document.getElementById('installment_months').required = true;
    } else {
        installmentOptions.style.display = 'none';
        installmentSummary.style.display = 'none';
        document.getElementById('installment_months').required = false;
    }
    
    calculateTotal();
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
    const installmentMonths = parseFloat(document.getElementById('installment_months').value) || 1;
    
    const subtotal = quantity * unitPrice;
    const discountAmount = (subtotal * discountPercentage) / 100;
    const total = subtotal - discountAmount;
    const monthlyPayment = total / installmentMonths;
    
    // Add smooth number animation
    animateValue(document.getElementById('subtotalDisplay'), '৳' + subtotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    animateValue(document.getElementById('discountDisplay'), '৳' + discountAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    animateValue(document.getElementById('totalDisplay'), '৳' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    animateValue(document.getElementById('monthlyPaymentDisplay'), '৳' + monthlyPayment.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}

function animateValue(element, newText) {
    element.style.transform = 'scale(1.1)';
    element.style.transition = 'all 0.2s ease';
    
    setTimeout(() => {
        element.textContent = newText;
        element.style.transform = 'scale(1)';
    }, 100);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateProductInfo();
    toggleInstallmentOptions();
    
    // Add smooth focus transitions
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-1px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection