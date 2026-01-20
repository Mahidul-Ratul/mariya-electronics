@extends('layout.app')

@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')

@section('header-actions')
    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
        <i class="fas fa-arrow-left me-2"></i> Back to Sales
    </a>
@endsection

@section('content')

<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="fas fa-lightbulb fa-2x text-warning me-3"></i>
                <div>
                    <h6 class="mb-1">Dual Product Selection Modes</h6>
                    <p class="mb-0 small">
                        Choose from existing inventory products or manually add custom products to your invoice. 
                        Switch between modes using the toggle buttons below.
                    </p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar fa-lg me-2"></i>
                        <h5 class="card-title mb-0">New Invoice</h5>
                    </div>
                    <small class="opacity-75">Invoice #{{ date('Y') }}-{{ str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) }}</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
                    @csrf

                    <!-- Customer Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Customer <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" name="customer_id" id="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="sale_date" class="form-label fw-bold">
                                <i class="fas fa-calendar me-1"></i>Sale Date
                            </label>
                            <input type="date" class="form-control form-control-lg" name="sale_date" id="sale_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <!-- Product Selection Section -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-box me-2"></i>Product Selection
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Selection Mode Toggle -->
                                    <div class="mb-3">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-toggle-on me-1"></i>Product Entry Mode
                                        </label>
                                        <div class="btn-group w-100" role="group" aria-label="Selection mode">
                                            <input type="radio" class="btn-check" name="selection_mode" id="mode_select" value="select" checked>
                                            <label class="btn btn-outline-primary btn-lg" for="mode_select">
                                                <i class="fas fa-list me-2"></i>Select from Existing Products
                                                <br><small class="d-block">Choose from inventory</small>
                                            </label>
                                            
                                            <input type="radio" class="btn-check" name="selection_mode" id="mode_manual" value="manual">
                                            <label class="btn btn-outline-success btn-lg" for="mode_manual">
                                                <i class="fas fa-edit me-2"></i>Manual Product Entry
                                                <br><small class="d-block">Add custom product</small>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Select from List Mode -->
                                    <div id="select_mode_section">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-search me-1"></i>Select Product
                                        </h6>
                                        
                                        <div class="mb-3">
                                            <select class="form-select form-select-lg" id="product_select" onchange="selectProductFromDropdown()">
                                                <option value="">Choose a product...</option>
                                                @foreach($products as $product)
                                                    @if($product->stock_quantity > 0)
                                                        <option value="{{ $product->id }}" 
                                                                data-name="{{ $product->name }}"
                                                                data-model="{{ $product->model }}"
                                                                data-price="{{ $product->selling_price }}"
                                                                data-stock="{{ $product->stock_quantity }}"
                                                                data-category="{{ $product->category->name ?? '' }}"
                                                                data-brand="{{ $product->brand->name ?? '' }}">
                                                            {{ $product->name }}{{ $product->model ? ' - ' . $product->model : '' }} 
                                                            (৳{{ number_format($product->selling_price, 2) }}) 
                                                            - Stock: {{ $product->stock_quantity }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="form-text">Products are grouped by availability. Out of stock items are disabled.</div>
                                        </div>

                                        <!-- Advanced Search & Filters -->
                                        <div class="collapse" id="advancedFilters">
                                            <div class="row g-2 mb-3 p-3 bg-light rounded">
                                                <div class="col-md-4">
                                                    <label class="form-label">Filter by Category</label>
                                                    <select class="form-select" id="category_filter" onchange="filterDropdownOptions()">
                                                        <option value="">All Categories</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Filter by Brand</label>
                                                    <select class="form-select" id="brand_filter" onchange="filterDropdownOptions()">
                                                        <option value="">All Brands</option>
                                                        @foreach($brands as $brand)
                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Search by Name</label>
                                                    <input type="text" class="form-control" id="product_search" placeholder="Type to search..." oninput="filterDropdownOptions()">
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-outline-secondary btn-sm mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                                            <i class="fas fa-search me-1"></i>Advanced Search & Filters
                                        </button>

                                        <!-- Selected Product Info -->
                                        <div class="alert alert-light border d-none" id="selected_product_details">
                                            <h6 class="mb-2"><i class="fas fa-info-circle me-1 text-info"></i>Selected Product</h6>
                                            <div id="selected_product_info"></div>
                                            
                                            <div class="row g-3 mt-2">
                                                <div class="col-md-6">
                                                    <label for="product_quantity" class="form-label">Quantity</label>
                                                    <div class="input-group">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                                        <input type="number" class="form-control text-center" id="product_quantity" value="1" min="1">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <button type="button" class="btn btn-success w-100" onclick="addProduct()">
                                                        <i class="fas fa-plus me-1"></i>Add to Invoice
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manual Entry Mode -->
                                    <div id="manual_mode_section" style="display: none;">
                                        <h6 class="text-success mb-3">
                                            <i class="fas fa-edit me-1"></i>Manual Product Entry
                                        </h6>
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label for="manual_product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="manual_product_name" placeholder="Enter product name" oninput="updateManualPreview()">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="manual_product_model" class="form-label">Model</label>
                                                <input type="text" class="form-control" id="manual_product_model" placeholder="Product model (optional)" oninput="updateManualPreview()">
                                            </div>
                                        </div>
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-4">
                                                <label for="manual_product_price" class="form-label">Unit Price (৳) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="manual_product_price" step="0.01" min="0" placeholder="0.00" oninput="updateManualPreview()">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="manual_product_quantity" class="form-label">Quantity</label>
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="decreaseManualQuantity()">-</button>
                                                    <input type="number" class="form-control text-center" id="manual_product_quantity" value="1" min="1" oninput="updateManualPreview()">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="increaseManualQuantity()">+</button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="manual_category" class="form-label">Category</label>
                                                <select class="form-select" id="manual_category" onchange="filterManualBrands(); updateManualPreview();">
                                                    <option value="">Select category (optional)</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label for="manual_brand" class="form-label">Brand</label>
                                                <select class="form-select" id="manual_brand" onchange="updateManualPreview()">
                                                    <option value="">Select brand (optional)</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}" data-category="{{ $brand->category_id ?? '' }}">{{ $brand->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="manual_product_description" class="form-label">Description</label>
                                                <textarea class="form-control" id="manual_product_description" rows="1" placeholder="Product description (optional)" oninput="updateManualPreview()"></textarea>
                                            </div>
                                        </div>

                                        <!-- Manual Product Preview -->
                                        <div class="alert alert-light border d-none" id="manual_product_preview">
                                            <h6 class="mb-2"><i class="fas fa-eye me-1 text-info"></i>Preview</h6>
                                            <div id="manual_preview_content"></div>
                                        </div>

                                        <button type="button" class="btn btn-success" onclick="addManualProduct()">
                                            <i class="fas fa-plus me-1"></i>Add Manual Product to Invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Summary -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-receipt me-2"></i>Invoice Summary
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="selected_products">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                            <p class="mb-0">No products added yet</p>
                                            <small>Use the product selection to add items</small>
                                        </div>
                                    </div>

                                    <!-- Totals -->
                                    <div class="border-top pt-3 mt-3">
                                        <div class="row mb-2">
                                            <div class="col">Subtotal:</div>
                                            <div class="col-auto fw-bold" id="subtotal_display">৳0.00</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <label for="discount" class="form-label mb-0">Discount:</label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="number" class="form-control form-control-sm" name="discount" id="discount" value="0" step="0.01" min="0" oninput="updateCalculations()" style="width: 100px;">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col"><h5>Total:</h5></div>
                                            <div class="col-auto"><h5 class="text-primary" id="total_display">৳0.00</h5></div>
                                        </div>
                                    </div>

                                    <!-- Hidden inputs for form submission -->
                                    <input type="hidden" name="subtotal" id="subtotal" value="0">
                                    <input type="hidden" name="total_amount" id="total_amount" value="0">

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                                        <i class="fas fa-save me-2"></i>Create Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
#mode_select:checked + label {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

#mode_manual:checked + label {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

#select_mode_section {
    min-height: 200px;
    transition: all 0.3s ease;
    border-left: 4px solid #007bff;
    padding-left: 15px;
    margin-left: 5px;
}

#manual_mode_section {
    min-height: 200px;
    transition: all 0.3s ease;
    border-left: 4px solid #28a745;
    padding-left: 15px;
    margin-left: 5px;
}

.mode-section-hidden {
    display: none !important;
}

.mode-section-visible {
    display: block !important;
}

#quick_search_results .alert {
    margin-bottom: 0.5rem;
}

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
console.log('JavaScript loading...');

// Variables
let allProducts = [];
let selectedProductId = null;
let products = [];

// Mode switching function
function toggleSelectionMode(mode) {
    console.log('Toggling mode to:', mode);
    
    const selectSection = document.getElementById('select_mode_section');
    const manualSection = document.getElementById('manual_mode_section');
    
    if (mode === 'select') {
        selectSection.style.display = 'block';
        manualSection.style.display = 'none';
        clearManualForm();
    } else if (mode === 'manual') {
        selectSection.style.display = 'none';
        manualSection.style.display = 'block';
        clearSelection();
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Set up mode switching
    document.querySelectorAll('input[name="selection_mode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Mode changed to:', this.value);
            toggleSelectionMode(this.value);
        });
    });
    
    // Initialize with select mode
    toggleSelectionMode('select');
});

// Product selection functions
function selectProductFromDropdown() {
    console.log('Product selected');
}

function clearSelection() {
    console.log('Selection cleared');
}

function clearManualForm() {
    console.log('Manual form cleared');
}

function updateManualPreview() {
    console.log('Manual preview updated');
}

function addProduct() {
    console.log('Product added');
}

function addManualProduct() {
    console.log('Manual product added');
}

function increaseQuantity() {
    console.log('Quantity increased');
}

function decreaseQuantity() {
    console.log('Quantity decreased');
}

function increaseManualQuantity() {
    const input = document.getElementById('manual_product_quantity');
    input.value = parseInt(input.value) + 1;
    updateManualPreview();
}

function decreaseManualQuantity() {
    const input = document.getElementById('manual_product_quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateManualPreview();
    }
}

function filterDropdownOptions() {
    console.log('Filters applied');
}

function filterManualBrands() {
    const categoryId = document.getElementById('manual_category').value;
    const brandSelect = document.getElementById('manual_brand');
    const brandOptions = brandSelect.querySelectorAll('option');
    
    brandOptions.forEach(option => {
        if (option.value === '') {
            option.style.display = '';
            return;
        }
        
        const brandCategory = option.dataset.category;
        if (!categoryId || brandCategory === categoryId) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    
    // Reset brand selection if current brand doesn't match category
    if (brandSelect.value && categoryId) {
        const selectedOption = brandSelect.querySelector(`option[value="${brandSelect.value}"]`);
        if (selectedOption && selectedOption.dataset.category !== categoryId) {
            brandSelect.value = '';
        }
    }
}

function updateCalculations() {
    console.log('Calculations updated');
}

console.log('JavaScript loaded successfully');
</script>
@endsection