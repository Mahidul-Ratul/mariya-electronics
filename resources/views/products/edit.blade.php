@extends('layout.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('header-actions')
    <div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View Product
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit me-2"></i>
                    <h5 class="mb-0">Edit Product: {{ $product->name }}</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold">Product Name *</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required placeholder="Enter product name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-bold">Category *</label>
                            <select class="form-select form-select-lg @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="brand_id" class="form-label fw-bold">Brand</label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" 
                                    id="brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="form-label fw-bold">Model</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                   id="model" name="model" value="{{ old('model', $product->model) }}" placeholder="Enter model">
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">Product Image <span class="text-muted">(Optional)</span></label>
                        @if($product->image)
                            <div class="mb-3">
                                <div class="card shadow-sm" style="max-width: 200px;">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 150px; object-fit: cover;" id="current-image">
                                    <div class="card-body py-2">
                                        <small class="text-muted">Current Image</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave empty to keep current image. Accepted formats: JPEG, PNG, JPG, GIF, WebP</div>
                        <div id="image-preview" class="mt-3" style="display: none;">
                            <div class="card shadow-sm" style="max-width: 200px;">
                                <img id="preview-img" src="#" alt="Preview" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body py-2">
                                    <small class="text-muted">New Image Preview</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="cost_price" class="form-label fw-bold">Cost Price (৳) *</label>
                            <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror" 
                                   id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required placeholder="0.00">
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="selling_price" class="form-label fw-bold">Selling Price (৳) *</label>
                            <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" 
                                   id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required placeholder="0.00">
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_discount" class="form-label fw-bold">Max Discount</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('max_discount') is-invalid @enderror" 
                                       id="max_discount" name="max_discount" value="{{ old('max_discount', $product->max_discount) }}" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('max_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="stock_quantity" class="form-label fw-bold">Stock Quantity *</label>
                            <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required placeholder="0">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="unit" class="form-label fw-bold">Unit</label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                   id="unit" name="unit" value="{{ old('unit', $product->unit) }}" placeholder="e.g. piece, kg, liter">
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="warranty_period" class="form-label fw-bold">Warranty Period</label>
                            <input type="text" class="form-control @error('warranty_period') is-invalid @enderror" 
                                   id="warranty_period" name="warranty_period" value="{{ old('warranty_period', $product->warranty_period) }}" 
                                   placeholder="e.g. 1 Year, 6 Months">
                            @error('warranty_period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">
                                Active Product
                            </label>
                            <div class="form-text">Enable this product for sales and display</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Image preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
}
</script>
@endsection