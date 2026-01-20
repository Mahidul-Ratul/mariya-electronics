@extends('layout.app')

@section('title', 'Installment Sale')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Installment Sale
                        </h4>
                        <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Sales
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('installment-sales.store') }}" method="POST" id="installmentSaleForm" enctype="multipart/form-data">
                        @csrf

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
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_mobile" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_mobile" name="customer_mobile" value="{{ old('customer_mobile') }}" required>
                                    @error('customer_mobile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_nid" class="form-label">NID Number</label>
                                    <input type="text" class="form-control" id="customer_nid" name="customer_nid" value="{{ old('customer_nid') }}">
                                    @error('customer_nid')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="customer_address" name="customer_address" rows="2" required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_nid_image" class="form-label">NID Image</label>
                                    <input type="file" class="form-control" id="customer_nid_image" name="customer_nid_image" accept="image/*">
                                    @error('customer_nid_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Customer's Spouse Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-user-friends me-2"></i>Customer's Spouse Information (Optional - Husband/Wife)
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_wife_name" class="form-label">Spouse Name</label>
                                    <input type="text" class="form-control" id="customer_wife_name" name="customer_wife_name" value="{{ old('customer_wife_name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_wife_nid" class="form-label">Spouse NID Number</label>
                                    <input type="text" class="form-control" id="customer_wife_nid" name="customer_wife_nid" value="{{ old('customer_wife_nid') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="customer_wife_nid_image" class="form-label">Spouse NID Image</label>
                                    <input type="file" class="form-control" id="customer_wife_nid_image" name="customer_wife_nid_image" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- Guarantor Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-user-shield me-2"></i>Guarantor Information <span class="text-danger">*</span>
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="guarantor_name" class="form-label">Guarantor Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guarantor_name" name="guarantor_name" value="{{ old('guarantor_name') }}" required>
                                    @error('guarantor_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="guarantor_mobile" class="form-label">Guarantor Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guarantor_mobile" name="guarantor_mobile" value="{{ old('guarantor_mobile') }}" required>
                                    @error('guarantor_mobile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="guarantor_nid" class="form-label">Guarantor NID</label>
                                    <input type="text" class="form-control" id="guarantor_nid" name="guarantor_nid" value="{{ old('guarantor_nid') }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="guarantor_address" class="form-label">Guarantor Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="guarantor_address" name="guarantor_address" rows="2" required>{{ old('guarantor_address') }}</textarea>
                                    @error('guarantor_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="guarantor_security_image" class="form-label">Security Document Image</label>
                                    <input type="file" class="form-control" id="guarantor_security_image" name="guarantor_security_image" accept="image/*">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="guarantor_security_info" class="form-label">Security Information</label>
                                    <textarea class="form-control" id="guarantor_security_info" name="guarantor_security_info" rows="2" placeholder="Details about security/collateral provided...">{{ old('guarantor_security_info') }}</textarea>
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
                                                    <select class="form-control" id="product_model" disabled>
                                                        <option value="">First select brand</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="product_id" class="form-label">Product</label>
                                                    <select class="form-control" id="product_id" disabled>
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
                                                    <button type="button" class="btn btn-info w-100" onclick="addProductToList()">
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
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products_list_body">
                                            <!-- Products will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden field for products data -->
                        <input type="hidden" name="products_data" id="products_data" value="">
                        
                        <!-- Hidden fields for validation -->
                        <input type="hidden" name="subtotal" id="subtotal_field" value="0">
                        <input type="hidden" name="total_amount" id="total_amount_field" value="0">

                        <!-- Payment Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-calculator me-2"></i>Payment Information
                                </h5>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label">Discount Amount</label>
                                    <input type="number" class="form-control" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}" step="0.01" min="0" onchange="calculateInstallments()">
                                    @error('discount_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="down_payment" class="form-label">Down Payment <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="down_payment" name="down_payment" value="{{ old('down_payment', 0) }}" step="0.01" min="0" onchange="calculateInstallments()" required>
                                    @error('down_payment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="total_installments" class="form-label">Number of Months <span class="text-danger">*</span></label>
                                    <select class="form-select" id="total_installments" name="total_installments" onchange="calculateInstallments()" required>
                                        <option value="">Select Months</option>
                                        <option value="1">1 Month</option>
                                        <option value="2">2 Months</option>
                                        <option value="3">3 Months</option>
                                        <option value="4">4 Months</option>
                                        <option value="5">5 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="7">7 Months</option>
                                        <option value="8">8 Months</option>
                                        <option value="9">9 Months</option>
                                        <option value="10">10 Months</option>
                                        <option value="11">11 Months</option>
                                        <option value="12">12 Months</option>
                                    </select>
                                    @error('total_installments')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="sale_date" class="form-label">Sale Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                                    @error('sale_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-3">Sale Summary</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Subtotal: </strong>
                                                <span id="subtotal_display">৳0.00</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Discount: </strong>
                                                <span id="discount_display">৳0.00</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Total Amount: </strong>
                                                <span id="total_display" class="text-primary fw-bold">৳0.00</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Down Payment: </strong>
                                                <span id="down_payment_display" class="text-success">৳0.00</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Remaining Amount: </strong>
                                                <span id="remaining_display" class="text-danger fw-bold">৳0.00</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Monthly Installment: </strong>
                                                <span id="monthly_installment_display" class="text-info fw-bold">৳0.00</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Number of Months: </strong>
                                                <span id="months_display" class="text-secondary fw-bold">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Installment Schedule -->
                        <div class="row mb-4" id="installment_schedule_section" style="display: none;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Installment Payment Schedule</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Installment #</th>
                                                        <th>Due Date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="schedule_table_body">
                                                    <!-- Populated by JavaScript -->
                                                </tbody>
                                            </table>
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
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-info btn-lg px-5">
                                <i class="fas fa-check me-2"></i>Create Installment Sale
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-lg ms-2 px-5">
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

// Filter brands by category
function filterBrandsByCategory() {
    const categoryId = document.getElementById('category_id').value;
    const brandSelect = document.getElementById('brand_id');
    const brandOptions = brandSelect.querySelectorAll('option');
    
    // Reset model and product selects
    resetProductSelection();
    
    if (!categoryId) {
        // Show all brands
        brandOptions.forEach(option => {
            if (option.value) option.style.display = '';
        });
        return;
    }
    
    // Filter brands based on selected category
    brandOptions.forEach(option => {
        if (!option.value) return; // Skip the placeholder option
        
        const brandCategories = option.dataset.categories.split(',');
        if (brandCategories.includes(categoryId)) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    brandSelect.value = '';
}

// Load models for selected brand
function loadModels() {
    const brandId = document.getElementById('brand_id').value;
    const categoryId = document.getElementById('category_id').value;
    const modelSelect = document.getElementById('product_model');
    
    // Reset product select
    document.getElementById('product_id').innerHTML = '<option value="">First select model</option>';
    document.getElementById('product_id').disabled = true;
    document.getElementById('unit_price').value = '';
    updateItemTotal();
    
    if (!brandId) {
        modelSelect.innerHTML = '<option value="">First select brand</option>';
        modelSelect.disabled = true;
        return;
    }
    
    // Fetch models via AJAX with category filter
    let url = `/api/brands/${brandId}/models`;
    if (categoryId) {
        url += `?category_id=${categoryId}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(models => {
            modelSelect.innerHTML = '<option value="">Select Model</option>';
            models.forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.textContent = model;
                modelSelect.appendChild(option);
            });
            modelSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading models:', error);
            alert('Error loading models');
        });
}

// Load products for selected model
function loadProducts() {
    const brandId = document.getElementById('brand_id').value;
    const categoryId = document.getElementById('category_id').value;
    const model = document.getElementById('product_model').value;
    const productSelect = document.getElementById('product_id');
    
    document.getElementById('unit_price').value = '';
    updateItemTotal();
    
    if (!model) {
        productSelect.innerHTML = '<option value="">First select model</option>';
        productSelect.disabled = true;
        return;
    }
    
    // Fetch products via AJAX with category filter
    let url = `/api/products/by-brand-model?brand_id=${brandId}&model=${model}`;
    if (categoryId) {
        url += `&category_id=${categoryId}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(products => {
            productSelect.innerHTML = '<option value="">Select Product</option>';
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} (Stock: ${product.stock_quantity})`;
                option.dataset.price = product.selling_price;
                option.dataset.stock = product.stock_quantity;
                option.dataset.name = product.name;
                option.dataset.brand = product.brand.name;
                option.dataset.model = product.model;
                productSelect.appendChild(option);
            });
            productSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading products:', error);
            alert('Error loading products');
        });
}

// Update item total when product/quantity changes
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('product_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('unit_price').value = selectedOption.dataset.price;
        } else {
            document.getElementById('unit_price').value = '';
        }
        updateItemTotal();
    });
    
    document.getElementById('quantity').addEventListener('input', updateItemTotal);
    document.getElementById('product_model').addEventListener('change', loadProducts);
    
    // Add listeners for installment calculation fields
    document.getElementById('discount_amount').addEventListener('input', calculateInstallments);
    document.getElementById('down_payment').addEventListener('input', calculateInstallments);
    document.getElementById('total_installments').addEventListener('change', calculateInstallments);
    document.getElementById('sale_date').addEventListener('change', calculateInstallments);
});

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
    const quantity = parseInt(document.getElementById('quantity').value);
    const unitPrice = parseFloat(document.getElementById('unit_price').value);
    
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
        calculateInstallments();
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
    calculateInstallments();
}

// Remove product from list
function removeProduct(index) {
    productsInCart.splice(index, 1);
    updateProductsList();
}

// Calculate installments
function calculateInstallments() {
    let subtotal = 0;
    
    productsInCart.forEach(product => {
        subtotal += product.quantity * product.unit_price;
    });
    
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const downPayment = parseFloat(document.getElementById('down_payment').value) || 0;
    const months = parseInt(document.getElementById('total_installments').value) || 0;
    const saleDate = document.getElementById('sale_date').value;
    const total = subtotal - discount;
    const remaining = total - downPayment;
    const monthlyInstallment = months > 0 ? remaining / months : 0;

    // Update display
    document.getElementById('subtotal_display').textContent = `৳${subtotal.toFixed(2)}`;
    document.getElementById('discount_display').textContent = `৳${discount.toFixed(2)}`;
    document.getElementById('total_display').textContent = `৳${total.toFixed(2)}`;
    document.getElementById('down_payment_display').textContent = `৳${downPayment.toFixed(2)}`;
    document.getElementById('remaining_display').textContent = `৳${remaining.toFixed(2)}`;
    document.getElementById('monthly_installment_display').textContent = `৳${monthlyInstallment.toFixed(2)}`;
    document.getElementById('months_display').textContent = months;
    
    // Update hidden fields for form submission
    document.getElementById('subtotal_field').value = subtotal.toFixed(2);
    document.getElementById('total_amount_field').value = total.toFixed(2);
    
    // Generate installment schedule
    if (months > 0 && monthlyInstallment > 0 && saleDate) {
        generateInstallmentSchedule(saleDate, months, monthlyInstallment);
    } else {
        document.getElementById('installment_schedule_section').style.display = 'none';
    }
}

// Generate installment schedule table
function generateInstallmentSchedule(saleDate, months, monthlyInstallment) {
    const tbody = document.getElementById('schedule_table_body');
    tbody.innerHTML = '';
    
    const startDate = new Date(saleDate);
    
    for (let i = 1; i <= months; i++) {
        const dueDate = new Date(startDate);
        dueDate.setMonth(dueDate.getMonth() + i);
        
        const row = `
            <tr>
                <td class="text-center"><strong>${i}</strong></td>
                <td>${dueDate.toLocaleDateString('en-GB')}</td>
                <td class="text-end"><strong>৳${monthlyInstallment.toFixed(2)}</strong></td>
                <td><span class="badge bg-warning">Pending</span></td>
            </tr>
        `;
        tbody.innerHTML += row;
    }
    
    document.getElementById('installment_schedule_section').style.display = 'block';
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
