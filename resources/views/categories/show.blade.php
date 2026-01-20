@extends('layout.app')

@section('title', 'Category Details')
@section('page-title', 'Category Details')

@section('header-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Categories
    </a>
    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit Category
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $category->name }}</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Name:</dt>
                    <dd class="col-sm-9">{{ $category->name }}</dd>
                    
                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ $category->description ?? 'No description provided' }}</dd>
                    
                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3">Products:</dt>
                    <dd class="col-sm-9">{{ $category->products->count() }} products</dd>
                    
                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $category->created_at->format('M d, Y g:i A') }}</dd>
                    
                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $category->updated_at->format('M d, Y g:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Products in this Category</h6>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($category->products->take(10) as $product)
                            <a href="{{ route('products.show', $product) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small>৳{{ number_format($product->selling_price, 2) }}</small>
                                </div>
                                <p class="mb-1">{{ $product->brand ?? 'No brand' }}</p>
                                <small>Stock: {{ $product->stock_quantity }}</small>
                            </a>
                        @endforeach
                    </div>
                    @if($category->products->count() > 10)
                        <div class="text-center mt-2">
                            <small class="text-muted">{{ $category->products->count() - 10 }} more products...</small>
                        </div>
                    @endif
                @else
                    <p class="text-muted">No products in this category yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection