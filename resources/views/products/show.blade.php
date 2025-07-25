@extends('layouts.app')

@section('header', 'Product Details')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Page Header -->
            <div class="modern-card card-gradient mb-4 animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-eye text-primary me-2"></i>
                                Product Details
                            </h4>
                            <p class="text-muted mb-0">View complete product information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary-modern">
                                <i class="fas fa-edit me-2"></i>
                                Edit Product
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Product Information -->
                <div class="col-xl-8 col-lg-7 mb-4">
                    <div class="modern-card animate-fade-in animate-delay-1">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Product Information
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary px-3 py-2">SKU: {{ $product->sku }}</span>
                                    @if($product->status == 'in_stock')
                                        <span class="status-badge status-success">In Stock</span>
                                    @elseif($product->status == 'low_stock')
                                        <span class="status-badge status-warning">Low Stock</span>
                                    @else
                                        <span class="status-badge status-danger">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                                                         <!-- Product Header -->
                             <div class="row mb-4 pb-4 border-bottom align-items-start">
                                 <div class="col-lg-7">
                                     <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>
                                     <p class="text-muted mb-0 fs-6">{{ $product->description ?: 'No description available' }}</p>
                                 </div>
                                 <div class="col-lg-5 text-lg-end mt-3 mt-lg-0 d-flex justify-content-lg-end justify-content-start">
                                     <div class="price-display">
                                         <div class="h3 fw-bold text-success mb-1">${{ number_format($product->price, 2) }}</div>
                                         <div class="text-muted small">Price per unit</div>
                                     </div>
                                 </div>
                             </div>

                            <!-- Product Details Grid -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Category</label>
                                        <div class="detail-value">
                                            @if($product->category)
                                                <div class="d-flex align-items-center">
                                                    <div class="category-icon me-3">
                                                        <i class="{{ $product->category->icon ?? 'fas fa-tag' }}" style="color: {{ $product->category->color }};"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $product->category->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Uncategorized</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Supplier</label>
                                        <div class="detail-value">
                                            @if($product->supplier)
                                                <div>
                                                    <div class="fw-semibold">{{ $product->supplier->name }}</div>
                                                    <div class="small text-muted">{{ $product->supplier->email }}</div>
                                                </div>
                                            @else
                                                <span class="text-muted">No supplier assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Current Stock</label>
                                        <div class="detail-value">
                                            <div class="d-flex align-items-center">
                                                <span class="stock-number {{ $product->quantity <= 10 ? 'text-danger' : ($product->quantity <= 20 ? 'text-warning' : 'text-success') }}">
                                                    {{ $product->quantity }}
                                                </span>
                                                <span class="text-muted ms-2">units</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Low Stock Alert</label>
                                        <div class="detail-value">
                                            <span class="fw-semibold">{{ $product->low_stock_threshold ?? 10 }}</span>
                                            <span class="text-muted ms-1">units</span>
                                        </div>
                                    </div>
                                </div>

                                @if($product->brand || $product->model)
                                <div class="col-12">
                                    <hr class="my-4">
                                    <h6 class="fw-bold text-dark mb-3">Product Specifications</h6>
                                </div>
                                @endif
                                
                                @if($product->brand)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Brand</label>
                                        <div class="detail-value fw-semibold">{{ $product->brand }}</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($product->model)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Model</label>
                                        <div class="detail-value fw-semibold">{{ $product->model }}</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($product->weight)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Weight</label>
                                        <div class="detail-value fw-semibold">{{ $product->weight }} kg</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($product->dimensions)
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <label class="detail-label">Dimensions</label>
                                        <div class="detail-value fw-semibold">{{ $product->dimensions }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions & Stats -->
                <div class="col-xl-4 col-lg-5">
                    <!-- Quick Actions -->
                    <div class="modern-card animate-fade-in animate-delay-2 mb-4">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-bolt text-primary me-2"></i>
                                Quick Actions
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-grid gap-3">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-primary-modern btn-lg">
                                        <i class="fas fa-edit me-2"></i>
                                        Edit Product
                                    </a>
                                @endif
                                
                                @if(auth()->user()->isStaff())
                                    <button class="btn btn-warning btn-lg" onclick="reportLowStock({{ $product->id }})">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Report Low Stock
                                    </button>
                                @endif
                                
                                <button class="btn btn-outline-success btn-lg" onclick="printProduct()">
                                    <i class="fas fa-print me-2"></i>
                                    Print Details
                                </button>
                                <button class="btn btn-outline-info btn-lg" onclick="shareProduct()">
                                    <i class="fas fa-share me-2"></i>
                                    Share Product
                                </button>
                                
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-lg w-100" 
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash me-2"></i>
                                            Delete Product
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Product Stats -->
                    <div class="modern-card animate-fade-in animate-delay-3 mb-4">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                Product Statistics
                            </h6>
                        </div>
                        <div class="card-body p-4">
                                                         <div class="row g-4">
                                 <div class="col-6">
                                     <div class="stat-card text-center p-3 rounded-3 bg-primary bg-opacity-10">
                                         <div class="stat-number text-primary">{{ max(0, intval($product->created_at->diffInDays())) }}</div>
                                         <div class="stat-label">Days Listed</div>
                                     </div>
                                 </div>
                                 <div class="col-6">
                                     <div class="stat-card text-center p-3 rounded-3 bg-success bg-opacity-10">
                                         <div class="stat-number text-success" title="${{ number_format($product->price * $product->quantity, 2) }}">${{ number_format($product->price * $product->quantity, 0) }}</div>
                                         <div class="stat-label">Total Value</div>
                                     </div>
                                 </div>
                             </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="modern-card animate-fade-in animate-delay-4">
                        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-history text-primary me-2"></i>
                                Recent Activity
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="activity-timeline">
                                <div class="activity-item">
                                    <div class="activity-marker bg-success"></div>
                                    <div class="activity-content">
                                        <div class="activity-title">Product Created</div>
                                        <div class="activity-time">{{ $product->created_at->format('M d, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                                @if($product->updated_at != $product->created_at)
                                <div class="activity-item">
                                    <div class="activity-marker bg-info"></div>
                                    <div class="activity-content">
                                        <div class="activity-title">Last Updated</div>
                                        <div class="activity-time">{{ $product->updated_at->format('M d, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Product Details Styling */
.price-display {
    text-align: center;
    padding: 16px 20px;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border-radius: 10px;
    border: 1px solid #bbf7d0;
    display: inline-block;
    min-width: 160px;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.1);
    transition: all 0.3s ease;
}

.price-display:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
    border-color: #86efac;
}

.detail-item {
    padding: 16px 0;
}

.detail-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.detail-value {
    font-size: 15px;
    color: #111827;
    line-height: 1.4;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(59, 130, 246, 0.1);
    font-size: 16px;
}

.stock-number {
    font-size: 24px;
    font-weight: 700;
}

/* Statistics Cards */
.stat-card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 16px 12px !important;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 6px;
    display: block;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.stat-label {
    font-size: 11px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1;
}

/* Activity Timeline */
.activity-timeline {
    position: relative;
    padding-left: 30px;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 12px;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.activity-item {
    position: relative;
    margin-bottom: 24px;
}

.activity-item:last-child {
    margin-bottom: 0;
}

.activity-marker {
    position: absolute;
    left: -24px;
    top: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #e5e7eb;
}

.activity-title {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
    margin-bottom: 2px;
}

.activity-time {
    font-size: 12px;
    color: #6b7280;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .price-display {
        text-align: center;
        padding: 14px 18px;
        margin: 0 auto;
        width: fit-content;
        max-width: 200px;
    }
    
    .stat-card {
        margin-bottom: 12px;
    }
    
    .detail-item {
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
}

/* Button improvements */
.btn-lg {
    padding: 12px 20px;
    font-weight: 600;
}

/* Card header improvements */
.card-header {
    border-bottom: 1px solid #f1f5f9;
}
</style>

<script>
function printProduct() {
    window.print();
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: 'Check out this product: {{ $product->name }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy URL to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Product URL copied to clipboard!');
        });
    }
}

function reportLowStock(productId) {
    if (confirm('Are you sure you want to report this item as low stock to administrators?')) {
        fetch(`/products/${productId}/report-low-stock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Low stock report sent to administrators successfully!');
            } else {
                alert('Error: ' + (data.message || 'Failed to report low stock'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while reporting low stock');
        });
    }
}
</script>
@endsection