@extends('layouts.app')

@section('header', 'Admin Dashboard')

<style>
/* Fix content area scrolling and layout */
.container-fluid {
    max-width: 100%;
    padding-bottom: 2rem;
    min-height: calc(100vh - 120px);
    overflow-y: visible;
}

/* Admin Dashboard Card Improvements */
.modern-card {
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 12px;
}

.modern-card .card-body {
    overflow: visible;
    padding: 2rem 1.5rem;
}

/* Statistics cards specific padding */
.modern-card .d-flex.align-items-center {
    padding: 0.5rem;
}

.modern-card .flex-grow-1.ms-3 {
    margin-left: 1.5rem !important;
    padding-right: 1rem;
}

/* Quick Actions and Recent Products cards */
.modern-card .card-header {
    padding: 1.5rem 1.5rem 0.5rem 1.5rem;
}

.modern-card .card-body.p-4 {
    padding: 1.5rem !important;
}

.modern-card .card-body.p-0 {
    padding: 0 !important;
}

/* Table padding fixes */
.modern-table table th,
.modern-table table td {
    padding: 1rem 1.5rem;
}

.modern-table table th:first-child,
.modern-table table td:first-child {
    padding-left: 2rem;
}

.modern-table table th:last-child,
.modern-table table td:last-child {
    padding-right: 2rem;
}

.modern-card .flex-grow-1 {
    min-width: 0;
    overflow: visible;
}

.modern-card .fw-bold.fs-3 {
    font-size: 1.5rem !important;
    line-height: 1.2;
    word-break: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

.modern-card .text-muted.small {
    font-size: 0.875rem;
    line-height: 1.2;
    margin-top: 0.25rem;
}

/* Ensure proper spacing for large numbers */
.modern-card .d-flex.align-items-center {
    gap: 0.75rem;
}

.modern-card .flex-shrink-0 {
    flex-shrink: 0;
}

/* Fix table layout */
.modern-table {
    overflow-x: auto;
    width: 100%;
}

.modern-table table {
    width: 100%;
    table-layout: auto;
}

/* Quick Actions section fixes */
.d-flex.flex-wrap.gap-3 {
    gap: 1rem !important;
    flex-wrap: wrap;
    margin: -0.5rem;
    padding: 0.5rem;
}

.d-flex.flex-wrap.gap-3 > * {
    margin: 0.5rem;
}

/* Button padding improvements */
.btn {
    padding: 0.75rem 1.5rem !important;
    border-radius: 8px;
}

.btn i {
    margin-right: 0.5rem;
}

/* Row spacing fixes */
.row.mb-4 {
    margin-bottom: 2rem !important;
}

/* Ensure content doesn't get cut off */
.content-area {
    padding-bottom: 3rem !important;
    overflow-y: auto !important;
    height: auto !important;
    min-height: calc(100vh - 120px) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modern-card .fw-bold.fs-3 {
        font-size: 1.25rem !important;
    }
    
    .modern-card .text-muted.small {
        font-size: 0.8rem;
    }
    
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

@media (max-width: 576px) {
    .modern-card .fw-bold.fs-3 {
        font-size: 1.1rem !important;
    }
    
    .d-flex.flex-wrap.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.flex-wrap.gap-3 > .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

@section('content')
<div class="container-fluid px-2">
    @if (session('success'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                <i class="fas fa-box text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 min-w-0">
                            <div class="fw-bold text-dark fs-3 text-truncate" title="{{ $totalProducts }}">{{ $totalProducts }}</div>
                            <div class="text-muted small">Total Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-dollar-sign text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 min-w-0">
                            <div class="fw-bold text-dark fs-3" title="${{ number_format($totalValue, 2) }}">{{ $formattedTotalValue }}</div>
                            <div class="text-muted small">Total Inventory Value</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-2">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-exclamation-triangle text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 min-w-0">
                            <div class="fw-bold text-dark fs-3 text-truncate" title="{{ $lowStockProducts }}">{{ $lowStockProducts }}</div>
                            <div class="text-muted small">Low Stock Items</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-chart-line text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3 min-w-0">
                            <div class="fw-bold text-dark fs-3 text-truncate" title="{{ $recentProducts->count() }}">{{ $recentProducts->count() }}</div>
                            <div class="text-muted small">Recent Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('products.create') }}" class="btn btn-primary-modern">
                            <i class="fas fa-plus me-2"></i>
                            Add New Product
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-box me-2"></i>
                            View All Products
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-tags me-2"></i>
                            Manage Categories
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-truck me-2"></i>
                            Manage Suppliers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-5">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Recent Products
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProducts as $product)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            @if($product->sku)
                                                <div class="small text-muted">SKU: {{ $product->sku }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->category)
                                                <span class="badge rounded-pill" style="background-color: {{ $product->category->color ?? '#6c757d' }}20; color: {{ $product->category->color ?? '#6c757d' }};">
                                                    {{ $product->category->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">Uncategorized</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold @if($product->quantity < 10) text-danger @elseif($product->quantity < 20) text-warning @else text-success @endif">
                                                {{ $product->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div>{{ $product->created_at->format('M d, Y') }}</div>
                                                <div class="text-muted">{{ $product->created_at->format('H:i') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-box-open fs-1 text-muted opacity-50 mb-3"></i>
                                            <h5>No products found</h5>
                                            <p class="text-muted mb-3">Get started by creating your first product.</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary-modern">
                                                <i class="fas fa-plus me-2"></i>
                                                Create First Product
                                            </a>
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
</div>
@endsection