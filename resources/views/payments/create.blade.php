@extends('layout.app')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Record Payment</h2>
                    <p class="text-muted mb-0">Add a new payment record</p>
                </div>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Payments
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('payments.store') }}">
                        @csrf
                        
                        <!-- Sale Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="sale_id" class="form-label">Select Sale *</label>
                                <select class="form-select" name="sale_id" id="sale_id" required>
                                    <option value="">Choose a sale...</option>
                                    @foreach($sales as $saleOption)
                                        <option value="{{ $saleOption->id }}" 
                                                {{ ($sale && $sale->id == $saleOption->id) || old('sale_id') == $saleOption->id ? 'selected' : '' }}
                                                data-customer="{{ $saleOption->customer ? $saleOption->customer->name : ($saleOption->customer_name ?? 'Direct Sale') }}"
                                                data-amount="{{ $saleOption->total_amount }}"
                                                data-paid="{{ $saleOption->payments->sum('amount') }}"
                                                data-remaining="{{ $saleOption->total_amount - $saleOption->payments->sum('amount') }}">
                                            Sale #{{ $saleOption->sale_number }} - 
                                            {{ $saleOption->customer ? $saleOption->customer->name : ($saleOption->customer_name ?? 'Direct Sale') }} - 
                                            ৳{{ number_format($saleOption->total_amount, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sale_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="installment_id" class="form-label">Installment (Optional)</label>
                                <select class="form-select" name="installment_id" id="installment_id">
                                    <option value="">General payment</option>
                                </select>
                                @error('installment_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sale Details Display -->
                        <div id="sale-details" class="alert alert-info d-none mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Customer:</strong>
                                    <div id="customer-name">-</div>
                                </div>
                                <div class="col-md-3">
                                    <strong>Total Amount:</strong>
                                    <div id="total-amount">৳0.00</div>
                                </div>
                                <div class="col-md-3">
                                    <strong>Paid Amount:</strong>
                                    <div id="paid-amount">৳0.00</div>
                                </div>
                                <div class="col-md-3">
                                    <strong>Remaining:</strong>
                                    <div id="remaining-amount">৳0.00</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Payment Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control" name="amount" id="amount" 
                                           value="{{ old('amount') }}" step="0.01" min="0" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="">Select method...</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_date" class="form-label">Payment Date *</label>
                                <input type="date" class="form-control" name="payment_date" id="payment_date" 
                                       value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="reference_number" id="reference_number" 
                                       value="{{ old('reference_number') }}" placeholder="Transaction ID, Check No, etc.">
                                @error('reference_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="3" 
                                      placeholder="Additional notes about this payment...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saleSelect = document.getElementById('sale_id');
    const installmentSelect = document.getElementById('installment_id');
    const saleDetails = document.getElementById('sale-details');
    const amountInput = document.getElementById('amount');

    saleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            // Show sale details
            document.getElementById('customer-name').textContent = selectedOption.dataset.customer || '-';
            document.getElementById('total-amount').textContent = '৳' + parseFloat(selectedOption.dataset.amount || 0).toFixed(2);
            document.getElementById('paid-amount').textContent = '৳' + parseFloat(selectedOption.dataset.paid || 0).toFixed(2);
            document.getElementById('remaining-amount').textContent = '৳' + parseFloat(selectedOption.dataset.remaining || 0).toFixed(2);
            
            // Set default payment amount to remaining amount
            amountInput.value = parseFloat(selectedOption.dataset.remaining || 0).toFixed(2);
            
            saleDetails.classList.remove('d-none');
            
            // Load installments for this sale
            loadInstallments(this.value);
        } else {
            saleDetails.classList.add('d-none');
            amountInput.value = '';
            installmentSelect.innerHTML = '<option value="">General payment</option>';
        }
    });

    function loadInstallments(saleId) {
        // You can implement AJAX call here to load installments
        // For now, we'll keep it simple
        installmentSelect.innerHTML = '<option value="">General payment</option>';
    }

    // Trigger change event if sale is pre-selected
    if (saleSelect.value) {
        saleSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection