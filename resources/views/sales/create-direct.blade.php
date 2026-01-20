@extends('layout.app')

@section('title', 'Direct Sale')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Direct Sale
                        </h4>
                        <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Sales
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales.store-direct') }}" method="POST" id="directSaleForm">
                        @csrf

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
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                    @error('customer_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="customer_address" name="customer_address" rows="2">{{ old('customer_address') }}</textarea>
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

                        <!-- Hidden input for products data -->
                        <input type="hidden" name="products_data" id="products_data" value="">

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
                                    <input type="number" class="form-control" id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}" step="0.01" min="0" onchange="calculateTotals()">
                                    @error('discount_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="paid_amount" class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="paid_amount" name="paid_amount" value="{{ old('paid_amount') }}" step="0.01" min="0" required onchange="calculateTotals()">
                                    @error('paid_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="payment_type" name="payment_type" required>
                                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bkash" {{ old('payment_type') == 'bkash' ? 'selected' : '' }}>Bkash</option>
                                        <option value="nagad" {{ old('payment_type') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                        <option value="bank" {{ old('payment_type') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden status field (auto-calculated) -->
                        <input type="hidden" name="status" id="status" value="completed">
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                    <div class="card-body">
                                        <h6 class="card-title">Sale Summary</h6>
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
                                                <strong>Total: </strong>
                                                <span id="total_display">৳0.00</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Due: </strong>
                                                <span id="due_display" class="text-danger">৳0.00</span>
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
                                    <input type="date" class="form-control" id="sale_date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                                    @error('sale_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Complete Direct Sale
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-lg ms-2">
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
let productsInCart = [];

document.addEventListener('DOMContentLoaded', function() {
    // Update item total on quantity change
    document.getElementById('quantity').addEventListener('input', updateItemTotal);
    
    calculateTotals();
});

// Filter brands by category
function filterBrandsByCategory() {
    console.log('filterBrandsByCategory called');
    const categoryId = document.getElementById('category_id').value;
    console.log('Selected category ID:', categoryId);
    const brandSelect = document.getElementById('brand_id');
    const brandOptions = brandSelect.querySelectorAll('option');
    
    brandOptions.forEach(option => {
        if (option.value === '') {
            option.style.display = '';
            return;
        }
        
        const brandCategories = option.dataset.categories ? option.dataset.categories.split(',') : [];
        console.log('Brand', option.text, 'categories:', brandCategories);
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
    console.log('loadModels called');
    const brandId = document.getElementById('brand_id').value;
    const categoryId = document.getElementById('category_id').value;
    const modelSelect = document.getElementById('product_model');
    
    console.log('Brand ID:', brandId, 'Category ID:', categoryId);
    
    if (!brandId) {
        console.log('No brand selected, disabling model select');
        modelSelect.innerHTML = '<option value="">First select brand</option>';
        modelSelect.disabled = true;
        resetProductSelection();
        return;
    }
    
    const url = `/api/products/by-brand/${brandId}${categoryId ? '?category_id=' + categoryId : ''}`;
    console.log('Fetching models from URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(models => {
            console.log('Models received:', models);
            
            // Clear and rebuild options properly
            modelSelect.innerHTML = '';
            
            // Add default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Model';
            modelSelect.appendChild(defaultOption);
            
            // Add model options
            models.forEach(model => {
                const option = document.createElement('option');
                option.value = model;
                option.textContent = model;
                modelSelect.appendChild(option);
            });
            
            modelSelect.disabled = false;
            console.log('Model select enabled. Disabled status:', modelSelect.disabled);
            console.log('Model select has', modelSelect.options.length, 'options');
            
            // Reset product and brand selections (but not model)
            document.getElementById('product_id').innerHTML = '<option value="">First select model</option>';
            document.getElementById('product_id').disabled = true;
            document.getElementById('unit_price').value = '';
            updateItemTotal();
            
            // Now bind events AFTER everything is set up
            // Remove any existing event listeners
            modelSelect.onclick = null;
            modelSelect.onchange = null;
            modelSelect.onfocus = null;
            
            // Add click event to test if element receives clicks
            modelSelect.addEventListener('click', function() {
                console.log('Model select clicked!');
            });
            
            modelSelect.addEventListener('focus', function() {
                console.log('Model select focused!');
            });
            
            // Bind change event properly
            modelSelect.addEventListener('change', function() {
                console.log('Model select changed to:', this.value);
                if (this.value) {
                    loadProducts();
                }
            });
            
            // Add visual debugging
            console.log('Model select computed style:', window.getComputedStyle(modelSelect));
            console.log('Model select position:', modelSelect.getBoundingClientRect());
            console.log('Model select z-index:', window.getComputedStyle(modelSelect).zIndex);
            console.log('Model select pointer-events:', window.getComputedStyle(modelSelect).pointerEvents);
            
            // Temporarily highlight the element to see if it's visible
            modelSelect.style.border = '3px solid red';
            setTimeout(() => {
                modelSelect.style.border = '';
            }, 3000);
        })
        .catch(error => {
            console.error('Error loading models:', error);
            modelSelect.innerHTML = '<option value="">Error loading models</option>';
            modelSelect.disabled = true;
        });
}

// Load products based on selected model
function loadProducts() {
    console.log('loadProducts called');
    const brandId = document.getElementById('brand_id').value;
    const categoryId = document.getElementById('category_id').value;
    const model = document.getElementById('product_model').value;
    const productSelect = document.getElementById('product_id');
    
    console.log('Brand ID:', brandId, 'Category ID:', categoryId, 'Model:', model);
    
    if (!brandId || !model) {
        console.log('Missing brand or model, disabling product select');
        productSelect.innerHTML = '<option value="">First select model</option>';
        productSelect.disabled = true;
        document.getElementById('unit_price').value = '';
        return;
    }
    
    const url = `/api/products/by-brand-model/${brandId}/${encodeURIComponent(model)}${categoryId ? '?category_id=' + categoryId : ''}`;
    console.log('Fetching products from URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(products => {
            console.log('Products received:', products);
            
            // Clear and rebuild options properly
            productSelect.innerHTML = '';
            
            // Add default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Product';
            productSelect.appendChild(defaultOption);
            
            // Add product options
            products.forEach(product => {
                console.log('Product data:', product); // Debug: see what data we get
                console.log('Brand data:', product.brand); // Debug: see brand object
                
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} (Stock: ${product.stock_quantity})`;
                option.setAttribute('data-name', product.name);
                option.setAttribute('data-brand', product.brand?.name || '');
                option.setAttribute('data-model', product.model || '');
                option.setAttribute('data-price', product.selling_price);
                option.setAttribute('data-stock', product.stock_quantity);
                
                console.log('Option brand attribute:', product.brand?.name || ''); // Debug
                console.log('Option model attribute:', product.model || ''); // Debug
                
                productSelect.appendChild(option);
            });
            
            productSelect.disabled = false;
            console.log('Product select enabled. Disabled status:', productSelect.disabled);
            console.log('Product select has', productSelect.options.length, 'options');
            
            // Remove any existing event listeners
            productSelect.onclick = null;
            productSelect.onchange = null;
            productSelect.onfocus = null;
            
            // Bind events properly
            productSelect.addEventListener('click', function() {
                console.log('Product select clicked!');
            });
            
            productSelect.addEventListener('change', function() {
                console.log('Product select changed to:', this.value);
                if (this.value) {
                    loadProductPrice();
                }
            });
            
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
    console.log('loadProductPrice called');
    const productSelect = document.getElementById('product_id');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    
    console.log('Selected product:', productSelect.value);
    console.log('Selected option data:', selectedOption ? selectedOption.dataset : 'No option selected');
    
    if (productSelect.value && selectedOption.dataset.price) {
        console.log('Setting unit price to:', selectedOption.dataset.price);
        document.getElementById('unit_price').value = selectedOption.dataset.price;
        updateItemTotal();
    } else {
        console.log('Clearing unit price');
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

    document.getElementById('subtotal_display').textContent = `৳${subtotal.toFixed(2)}`;
    document.getElementById('discount_display').textContent = `৳${discount.toFixed(2)}`;
    document.getElementById('total_display').textContent = `৳${total.toFixed(2)}`;
    document.getElementById('due_display').textContent = `৳${due.toFixed(2)}`;
    document.getElementById('due_display').className = due > 0 ? 'text-danger' : 'text-success';
    
    // Auto-set status based on due amount
    const statusField = document.getElementById('status');
    if (statusField) {
        statusField.value = due <= 0 ? 'completed' : 'due';
    }
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