@extends('layouts.app')

@section('header', 'Staff Dashboard')

@section('content')
<div class="container-fluid px-2">
    <!-- Welcome Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card card-gradient animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">
                                Welcome back, {{ Auth::user()->name }}! 👋
                            </h4>
                            <p class="text-muted mb-0">
                                Manage inventory and track stock levels
                            </p>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-10" style="width: 120px; height: 120px; overflow: hidden;">
                            <i class="fas fa-boxes" style="font-size: 6rem; position: absolute; top: 10px; right: 10px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                <i class="fas fa-box text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $totalProducts }}</div>
                            <div class="text-muted small">Total Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-2">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-dollar-sign text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">${{ number_format($totalValue, 0) }}</div>
                            <div class="text-muted small">Inventory Value</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-exclamation-triangle text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $lowStockItems }}</div>
                            <div class="text-muted small">Low Stock Items</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                                <i class="fas fa-times-circle text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $outOfStockItems }}</div>
                            <div class="text-muted small">Out of Stock</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Products Needing Attention -->
        <div class="col-xl-8">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                Products Needing Attention
                            </h6>
                            <p class="text-muted small mb-0">Items with low or zero stock</p>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-warning">
                            View All Products
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productsNeedingAttention as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-2 p-2 bg-light">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-muted">{{ $product->sku ?? 'No SKU' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $product->quantity == 0 ? 'text-danger' : ($product->quantity <= 5 ? 'text-danger' : 'text-warning') }}">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->quantity == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->quantity <= 5)
                                            <span class="badge bg-danger">Critical</span>
                                        @else
                                            <span class="badge bg-warning">Low Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @can('update', $product)
                                            <form method="POST" action="{{ route('products.update-stock', $product) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $product->quantity }}" min="0" class="form-control form-control-sm d-inline" style="width: 80px;">
                                                <button type="submit" class="btn btn-sm btn-outline-success ms-1">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </form>
                                            @endcan
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-success">
                                            <i class="fas fa-check-circle fs-1 mb-3"></i>
                                            <p class="mb-0">All products are well stocked!</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Overview -->
        <div class="col-xl-4">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-tags text-primary me-2"></i>
                                Categories Overview
                            </h6>
                            <p class="text-muted small mb-0">Products by category</p>
                        </div>
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($categoriesData as $category)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-3">
                            <div>
                                <div class="fw-semibold">{{ $category->name }}</div>
                                <small class="text-muted">{{ $category->products_count }} products</small>
                            </div>
                            <div class="text-end">
                                @if($category->low_stock_count > 0)
                                    <span class="badge bg-warning rounded-pill mb-1">{{ $category->low_stock_count }} low</span>
                                @endif
                                <div class="small text-muted">Total: {{ $category->products_count }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center py-4 border-0">
                            <div class="text-muted">
                                <i class="fas fa-tag fs-1 mb-3"></i>
                                <p class="mb-0">No categories found</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-clock text-primary me-2"></i>
                                Recently Added Products
                            </h6>
                            <p class="text-muted small mb-0">Latest products in the system</p>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-2 p-2 bg-light">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-muted">{{ $product->sku ?? 'No SKU' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge rounded-pill bg-secondary">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold">${{ number_format($product->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $product->quantity == 0 ? 'text-danger' : ($product->quantity <= 10 ? 'text-warning' : 'text-success') }}">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small text-muted">{{ $product->created_at->format('M j, Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('update', $product)
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateStock{{ $product->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box fs-1 mb-3"></i>
                                            <p class="mb-0">No products found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h6>
                    <p class="text-muted small mb-0">Staff shortcuts and tools</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('products.index') }}" class="btn btn-primary-modern w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-search fs-4 mb-2"></i>
                                    <div>Search Products</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('products.index', ['status' => 'low_stock']) }}" class="btn btn-outline-modern w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle fs-4 mb-2"></i>
                                    <div>Low Stock Items</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-modern w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-tags fs-4 mb-2"></i>
                                    <div>View Categories</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-modern w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-users fs-4 mb-2"></i>
                                    <div>View Customers</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modals -->
@foreach($recentProducts as $product)
<div class="modal fade" id="updateStock{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stock - {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('products.update-stock', $product) }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Current Stock: <strong>{{ $product->quantity }}</strong></label>
                        <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Note (Optional)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Reason for stock update..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection