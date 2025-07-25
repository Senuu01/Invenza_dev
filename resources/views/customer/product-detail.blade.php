@extends('layouts.app')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <span>Product Details</span>
    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Catalog
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 300px;">
                                    <i class="fas fa-box fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="fw-bold">{{ $product->name }}</h2>
                                @if($product->quantity > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success fs-6">
                                        In Stock
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger fs-6">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-muted mb-4">{{ $product->description }}</p>
                            
                            <div class="mb-3">
                                <span class="badge rounded-pill bg-light text-dark">
                                    {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>Price:</strong>
                                        <div class="h3 text-success fw-bold">${{ number_format($product->price, 2) }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Available:</strong>
                                        <div class="h5 fw-bold">{{ $product->quantity }} units</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($product->sku)
                                <div class="mb-3">
                                    <strong>SKU:</strong> 
                                    <span class="font-monospace">{{ $product->sku }}</span>
                                </div>
                            @endif
                            
                            @if($product->supplier)
                                <div class="mb-3">
                                    <strong>Supplier:</strong> {{ $product->supplier->name }}
                                </div>
                            @endif
                            
                            <div class="d-flex gap-2">
                                @if($product->quantity > 0)
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check me-2"></i>
                                        Available for Order
                                    </button>
                                @else
                                    <button class="btn btn-danger" disabled>
                                        <i class="fas fa-times me-2"></i>
                                        Out of Stock
                                    </button>
                                @endif
                                
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Back to Catalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection