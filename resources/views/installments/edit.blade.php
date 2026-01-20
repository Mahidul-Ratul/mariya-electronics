@extends('layout.app')

@section('title', 'Edit Installment Sale')
@section('page-title', 'Edit Installment Sale')
@section('page-subtitle', 'Update installment sale information')

@section('header-actions')
    <a href="{{ route('installment-sales.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('installment-sales.update', $installmentSale->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Customer Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="fas fa-user me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                               value="{{ old('customer_name', $installmentSale->customer_name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="customer_mobile" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customer_mobile" name="customer_mobile" 
                               value="{{ old('customer_mobile', $installmentSale->customer_mobile) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="customer_nid" class="form-label">NID Number</label>
                        <input type="text" class="form-control" id="customer_nid" name="customer_nid" 
                               value="{{ old('customer_nid', $installmentSale->customer_nid) }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="customer_address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="customer_address" name="customer_address" rows="2" required>{{ old('customer_address', $installmentSale->customer_address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Guarantor Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="fas fa-user-shield me-2"></i>Guarantor Information
                    </h5>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="guarantor_name" class="form-label">Guarantor Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="guarantor_name" name="guarantor_name" 
                               value="{{ old('guarantor_name', $installmentSale->guarantor_name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="guarantor_mobile" class="form-label">Guarantor Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="guarantor_mobile" name="guarantor_mobile" 
                               value="{{ old('guarantor_mobile', $installmentSale->guarantor_mobile) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="guarantor_nid" class="form-label">Guarantor NID</label>
                        <input type="text" class="form-control" id="guarantor_nid" name="guarantor_nid" 
                               value="{{ old('guarantor_nid', $installmentSale->guarantor_nid) }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="guarantor_address" class="form-label">Guarantor Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="guarantor_address" name="guarantor_address" rows="2" required>{{ old('guarantor_address', $installmentSale->guarantor_address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="border-bottom pb-2 mb-3 text-primary">
                        <i class="fas fa-money-bill-wave me-2"></i>Payment Information
                    </h5>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="discount_amount" class="form-label">Discount Amount</label>
                        <input type="number" class="form-control" id="discount_amount" name="discount_amount" 
                               value="{{ old('discount_amount', $installmentSale->discount_amount) }}" step="0.01" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="down_payment" class="form-label">Down Payment <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="down_payment" name="down_payment" 
                               value="{{ old('down_payment', $installmentSale->down_payment) }}" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="total_installments" class="form-label">Number of Months <span class="text-danger">*</span></label>
                        <select class="form-select" id="total_installments" name="total_installments" required>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $installmentSale->total_installments == $i ? 'selected' : '' }}>
                                    {{ $i }} Month{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="active" {{ $installmentSale->payment_status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ $installmentSale->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="defaulted" {{ $installmentSale->payment_status == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                            <option value="cancelled" {{ $installmentSale->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-3">Sale Summary</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Subtotal: </strong>৳{{ number_format($installmentSale->subtotal, 2) }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Total Amount: </strong>৳{{ number_format($installmentSale->total_amount, 2) }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Monthly Installment: </strong>৳{{ number_format($installmentSale->monthly_installment, 2) }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Sale Date: </strong>{{ \Carbon\Carbon::parse($installmentSale->sale_date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $installmentSale->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save me-2"></i>Update Installment Sale
                </button>
                <a href="{{ route('installment-sales.index') }}" class="btn btn-secondary btn-lg ms-2 px-5">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
