@extends('layout.app')

@section('title', 'Brand Details')
@section('page-title', 'Brand Details')

@section('header-actions')
    <a href="{{ route('brands.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Brands
    </a>
    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit Brand
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $brand->name }}</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Name:</dt>
                    <dd class="col-sm-9">{{ $brand->name }}</dd>
                    
                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ $brand->description ?? 'No description provided' }}</dd>
                    
                    <dt class="col-sm-3">Status:</dt>
                    <dd class="col-sm-9">
                        @if($brand->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3">Products:</dt>
                    <dd class="col-sm-9">{{ $brand->products->count() }} products</dd>
                    
                    <dt class="col-sm-3">Created:</dt>
                    <dd class="col-sm-9">{{ $brand->created_at->format('M d, Y g:i A') }}</dd>
                    
                    <dt class="col-sm-3">Updated:</dt>
                    <dd class="col-sm-9">{{ $brand->updated_at->format('M d, Y g:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Products from this Brand</h6>
            </div>
            <div class="card-body">
                @if($brand->products->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($brand->products->take(10) as $product)
                            <a href="{{ route('products.show', $product) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small>৳{{ number_format($product->selling_price, 2) }}</small>
                                </div>
                                <p class="mb-1">{{ $product->model ?? 'No model specified' }}</p>
                                <small>Stock: {{ $product->stock_quantity }}</small>
                            </a>
                        @endforeach
                    </div>
                    @if($brand->products->count() > 10)
                        <div class="text-center mt-2">
                            <small class="text-muted">{{ $brand->products->count() - 10 }} more products...</small>
                        </div>
                    @endif
                @else
                    <p class="text-muted">No products from this brand yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection