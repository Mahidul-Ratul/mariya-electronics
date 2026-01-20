@extends('layout.app')

@section('title', 'Edit Sale')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Edit Sale #{{ $sale->sale_number }}
                        </h4>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Details
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales.update', $sale) }}" method="POST" id="editSaleForm">
                        @csrf
                        @method('PUT')

                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-user me-2"></i>Customer Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name', $sale->customer_name ?? $sale->customer->name ?? '') }}" required>
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_mobile" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_mobile" name="customer_mobile" value="{{ old('customer_mobile', $sale->customer_mobile ?? $sale->customer->phone ?? '') }}" required>
                                    @error('customer_mobile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="customer_address" name="customer_address" rows="2">{{ old('customer_address', $sale->customer_address ?? $sale->customer->address ?? '') }}</textarea>
                                    @error('customer_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Product Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-box me-2"></i>Add Products
                                </h5>
                                
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">Category</label>
                                                    <select class="form-control" id="category_id" onchange="filterBrandsByCategory()">
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="brand_id" class="form-label">Brand</label>
                                                    <select class="form-control" id="brand_id" onchange="loadModels()">
                                                        <option value="">Select Brand</option>
                                                        @foreach($brands as $brand)
                                                            <option value="{{ $brand->id }}" data-categories="{{ $brand->category_ids->implode(',') }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="product_model" class="form-label">Model</label>
                                                    <select class="form-control" id="product_model" onchange="loadProducts()" disabled>
                                                        <option value="">First select brand</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="product_id" class="form-label">Product</label>
                                                    <select class="form-control" id="product_id" onchange="loadProductPrice()" disabled>
                                                        <option value="">First select model</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="quantity" value="1" min="1">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="unit_price" class="form-label">Unit Price <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="unit_price" step="0.01" min="0" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="item_total" class="form-label">Item Total</label>
                                                    <input type="text" class="form-control" id="item_total" readonly value="৳0.00">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-success w-100" onclick="addProductToList()">
                                                        <i class="fas fa-plus me-2"></i>Add to List
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products List -->
                        <div class="row mb-4" id="products_list_section" style="display: none;">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-list me-2"></i>Selected Products
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th>Product</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                                <th width="80">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products_list_body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Sale Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-calculator me-2"></i>Sale Summary
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label">Discount Amount</label>
                                    <input type="number" class="form-control" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', $sale->discount_amount ?? 0) }}" step="0.01" min="0" onchange="calculateTotals()">
                                    @error('discount_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="paid_amount" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', $sale->paid_amount ?? 0) }}" step="0.01" min="0" required onchange="calculateTotals()">
                                    @error('paid_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input for products data -->
                        <input type="hidden" name="products_data" id="products_data" value="">
                        
                        <!-- Hidden fields for validation -->
                        <input type="hidden" name="subtotal" id="subtotal_field" value="{{ $sale->subtotal ?? 0 }}">
                        <input type="hidden" name="total_amount" id="total_amount_field" value="{{ $sale->total_amount ?? 0 }}">
                        <input type="hidden" name="payment_type" id="payment_type_field" value="{{ $sale->payment_type ?? 'cash' }}">
                        <input type="hidden" name="status" id="status_field" value="{{ $sale->status ?? 'completed' }}">

                        <!-- Calculation Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Sale Summary</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Subtotal: </strong>
                                                <span id="subtotal_display">৳{{ number_format($sale->subtotal ?? 0, 2) }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Discount: </strong>
                                                <span id="discount_display">৳{{ number_format($sale->discount_amount ?? 0, 2) }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Total: </strong>
                                                <span id="total_display">৳{{ number_format($sale->total_amount ?? 0, 2) }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Due: </strong>
                                                <span id="due_display" class="text-danger">৳{{ number_format(($sale->total_amount ?? 0) - ($sale->paid_amount ?? 0), 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sale_date" class="form-label">Sale Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="sale_date" name="sale_date" value="{{ old('sale_date', $sale->sale_date ? $sale->sale_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                                    @error('sale_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $sale->notes) }}</textarea>
                                    @error('notes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Sale
                            </button>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-lg ms-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global products array
let productsInCart = [];

document.addEventListener('DOMContentLoaded', function() {
    // Load existing products if available
    @if(isset($sale->products_data) && $sale->products_data)
        try {
            const existingProducts = @json($sale->products_list);
            if (Array.isArray(existingProducts) && existingProducts.length > 0) {
                productsInCart = existingProducts;
                updateProductsList();
            }
        } catch(e) {
            console.error('Error loading existing products:', e);
        }
    @endif
    
    // Update item total on quantity change
    document.getElementById('quantity').addEventListener('input', updateItemTotal);
    
    calculateTotals();
});

// Filter brands by category
function filterBrandsByCategory() {
    const categoryId = document.getElementById('category_id').value;
    const brandSelect = document.getElementById('brand_id');
    const brandOptions = brandSelect.querySelectorAll('option');
    
    brandOptions.forEach(option => {
        if (option.value === '') {
            option.style.display = '';
            return;
        }
        
        const brandCategories = option.dataset.categories ? option.dataset.categories.split(',') : [];
        if (!categoryId || brandCategories.includes(categoryId)) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    // Reset brand selection if current selection is not visible
    if (brandSelect.value) {
        const selectedOption = brandSelect.querySelector(`option[value="${brandSelect.value}"]`);
        if (selectedOption && selectedOption.style.display === 'none') {
            brandSelect.value = '';
        }
    }
    
    resetProductSelection();
}

// Load models based on selected brand
function loadModels() {
    const brandId = document.getElementById('brand_id').value;
    const categoryId = document.getElementById('category_id').value;
    const modelSelect = document.getElementById('product_model');
    
    if (!brandId) {
        modelSelect.innerHTML = '<option value="">First select brand</option>';
        modelSelect.disabled = true;
        resetProductSelection();
        return;
    }
    
    const url = `/api/products/by-brand/${brandId}${categoryId ? '?category_id=' + categoryId : ''}`;
    fetch(url)
        .then(response => response.json())
        .then(models => {
            modelSelect.innerHTML = '<option value="">Select Model</option>';
            models.forEach(model => {
                modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
            });
            modelSelect.disabled = false;
            resetProductSelection();
        })
        .catch(error => {
            console.error('Error loading models:', error);
            modelSelect.innerHTML = '<option value="">Error loading models</option>';
            modelSelect.disabled = true;
        });
}

// Load products based on selected model
function loadProducts() {
    const brandId = document.getElementById('brand_id').value;
    const categoryId = document.getElementById('category_id').value;
    const model = document.getElementById('product_model').value;
    const productSelect = document.getElementById('product_id');
    
    if (!brandId || !model) {
        productSelect.innerHTML = '<option value="">First select model</option>';
        productSelect.disabled = true;
        document.getElementById('unit_price').value = '';
        return;
    }
    
    const url = `/api/products/by-brand-model/${brandId}/${encodeURIComponent(model)}${categoryId ? '?category_id=' + categoryId : ''}`;
    fetch(url)
        .then(response => response.json())
        .then(products => {
            productSelect.innerHTML = '<option value="">Select Product</option>';
            products.forEach(product => {
                productSelect.innerHTML += `<option value="${product.id}" 
                    data-name="${product.name}"
                    data-brand="${product.brand?.name || ''}"
                    data-model="${product.model || ''}"
                    data-price="${product.selling_price}"
                    data-stock="${product.stock_quantity}">
                    ${product.name} (Stock: ${product.stock_quantity})
                </option>`;
            });
            productSelect.disabled = false;
            document.getElementById('unit_price').value = '';
            updateItemTotal();
        })
        .catch(error => {
            console.error('Error loading products:', error);
            productSelect.innerHTML = '<option value="">Error loading products</option>';
            productSelect.disabled = true;
        });
}

// Load product price when product is selected
function loadProductPrice() {
    const productSelect = document.getElementById('product_id');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    
    if (productSelect.value && selectedOption.dataset.price) {
        document.getElementById('unit_price').value = selectedOption.dataset.price;
        updateItemTotal();
    } else {
        document.getElementById('unit_price').value = '';
        updateItemTotal();
    }
}

// Update item total display
function updateItemTotal() {
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const total = quantity * unitPrice;
    document.getElementById('item_total').value = `৳${total.toFixed(2)}`;
}

// Add product to list
function addProductToList() {
    const productSelect = document.getElementById('product_id');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    
    if (!productSelect.value) {
        alert('Please select a product');
        return;
    }
    
    if (quantity <= 0) {
        alert('Please enter valid quantity');
        return;
    }
    
    const productData = {
        product_id: productSelect.value,
        product_name: selectedOption.dataset.name,
        brand: selectedOption.dataset.brand,
        model: selectedOption.dataset.model,
        quantity: quantity,
        unit_price: unitPrice,
        type: 'inventory'
    };
    
    productsInCart.push(productData);
    updateProductsList();
    resetForm();
}

// Update products list display
function updateProductsList() {
    const tbody = document.getElementById('products_list_body');
    const listSection = document.getElementById('products_list_section');
    
    if (productsInCart.length === 0) {
        listSection.style.display = 'none';
        tbody.innerHTML = '';
        document.getElementById('products_data').value = '';
        calculateTotals();
        return;
    }
    
    listSection.style.display = 'block';
    tbody.innerHTML = '';
    
    productsInCart.forEach((product, index) => {
        const total = product.quantity * product.unit_price;
        const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${product.product_name}</td>
                <td>${product.brand || '-'}</td>
                <td>${product.model || '-'}</td>
                <td>${product.quantity}</td>
                <td>৳${product.unit_price.toFixed(2)}</td>
                <td>৳${total.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    document.getElementById('products_data').value = JSON.stringify(productsInCart);
    calculateTotals();
}

// Remove product from list
function removeProduct(index) {
    productsInCart.splice(index, 1);
    updateProductsList();
}

// Calculate totals
function calculateTotals() {
    let subtotal = 0;
    
    productsInCart.forEach(product => {
        subtotal += product.quantity * product.unit_price;
    });
    
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
    const total = subtotal - discount;
    const due = total - paidAmount;

    // Auto-calculate status based on due amount
    const status = due <= 0 ? 'completed' : 'due';

    // Update display
    document.getElementById('subtotal_display').textContent = `৳${subtotal.toFixed(2)}`;
    document.getElementById('discount_display').textContent = `৳${discount.toFixed(2)}`;
    document.getElementById('total_display').textContent = `৳${total.toFixed(2)}`;
    document.getElementById('due_display').textContent = `৳${due.toFixed(2)}`;
    document.getElementById('due_display').className = due > 0 ? 'text-danger' : 'text-success';
    
    // Update hidden fields for form submission
    document.getElementById('subtotal_field').value = subtotal.toFixed(2);
    document.getElementById('total_amount_field').value = total.toFixed(2);
    document.getElementById('status_field').value = status;
}

// Reset form after adding product
function resetForm() {
    document.getElementById('category_id').value = '';
    document.getElementById('brand_id').value = '';
    document.getElementById('product_model').innerHTML = '<option value="">First select brand</option>';
    document.getElementById('product_model').disabled = true;
    document.getElementById('product_id').innerHTML = '<option value="">First select model</option>';
    document.getElementById('product_id').disabled = true;
    document.getElementById('quantity').value = 1;
    document.getElementById('unit_price').value = '';
    document.getElementById('item_total').value = '৳0.00';
    
    // Show all brands again
    document.querySelectorAll('#brand_id option').forEach(option => {
        option.style.display = '';
    });
}

// Reset product selection only
function resetProductSelection() {
    document.getElementById('product_model').innerHTML = '<option value="">First select brand</option>';
    document.getElementById('product_model').disabled = true;
    document.getElementById('product_id').innerHTML = '<option value="">First select model</option>';
    document.getElementById('product_id').disabled = true;
    document.getElementById('unit_price').value = '';
    updateItemTotal();
}
</script>
@endsection
