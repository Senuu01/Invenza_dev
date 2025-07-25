@extends('layouts.app')

@section('header', 'Product Catalog')

@section('content')
<div class="container-fluid px-2">
    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="modern-card h-100 animate-fade-in">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">{{ $product->name }}</h5>
                            @if($product->quantity > 0)
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    In Stock
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-muted small mb-3">{{ Str::limit($product->description, 80) }}</p>
                        
                        <div class="mb-3">
                            <span class="badge rounded-pill bg-light text-dark">
                                {{ $product->category ? $product->category->name : 'Uncategorized' }}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="h4 fw-bold text-success mb-1">${{ number_format($product->price, 2) }}</p>
                                <p class="small text-muted mb-0">{{ $product->quantity }} available</p>
                            </div>
                            <a href="{{ route('customer.products.show', $product) }}" class="btn btn-primary-modern btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fs-1 text-muted opacity-50 mb-3"></i>
                        <h5>No products available</h5>
                        <p class="text-muted mb-0">Check back later for new products.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection