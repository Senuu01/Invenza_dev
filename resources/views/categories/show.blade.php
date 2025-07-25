@extends('layouts.app')

@section('header', 'Category Details')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="modern-card card-gradient mb-4 animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-3 p-3" style="background-color: {{ $category->color }}20;">
                                    <i class="{{ $category->icon ?? 'fas fa-tag' }} fs-3" style="color: {{ $category->color }};"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold text-dark">{{ $category->name }}</h4>
                                <p class="text-muted mb-0">{{ $category->products->count() }} products in this category</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Categories
                            </a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary-modern">
                                <i class="fas fa-edit me-2"></i>
                                Edit Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Category Information -->
                <div class="col-lg-4 mb-4">
                    <div class="modern-card animate-fade-in">
                        <div class="card-header bg-white border-0">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Category Information
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Category Name</label>
                                <div class="fw-bold">{{ $category->name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Description</label>
                                <div>{{ $category->description ?? 'No description provided' }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Color</label>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-2 me-2" style="width: 20px; height: 20px; background-color: {{ $category->color }};"></div>
                                    <span class="font-monospace">{{ $category->color }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Icon</label>
                                <div class="d-flex align-items-center">
                                    <i class="{{ $category->icon ?? 'fas fa-tag' }} me-2" style="color: {{ $category->color }};"></i>
                                    <span class="font-monospace">{{ $category->icon ?? 'fas fa-tag' }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Total Products</label>
                                <div class="fw-bold fs-4" style="color: {{ $category->color }};">{{ $category->products->count() }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Created</label>
                                <div>{{ $category->created_at->format('M d, Y \a\t H:i') }}</div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold text-muted small">Last Updated</label>
                                <div>{{ $category->updated_at->format('M d, Y \a\t H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products in Category -->
                <div class="col-lg-8 mb-4">
                    <div class="modern-card animate-fade-in animate-delay-1">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-box text-primary me-2"></i>
                                Products in {{ $category->name }}
                            </h6>
                            <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-sm btn-primary-modern">
                                <i class="fas fa-plus me-1"></i>
                                Add Product
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if($category->products->count() > 0)
                                <div class="modern-table">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->products as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                                 class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-box text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-semibold">{{ $product->name }}</div>
                                                            <div class="small text-muted">{{ Str::limit($product->description, 50) }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="font-monospace">{{ $product->sku }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">${{ number_format($product->price, 2) }}</span>
                                                </td>
                                                <td>
                                                    @if($product->quantity <= 10)
                                                        <span class="badge bg-danger">{{ $product->quantity }}</span>
                                                    @elseif($product->quantity <= 50)
                                                        <span class="badge bg-warning">{{ $product->quantity }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $product->quantity }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($product->quantity > 0)
                                                        <span class="badge bg-success bg-opacity-10 text-success">In Stock</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger">Out of Stock</span>
                                                    @endif
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-box-open fs-1 text-muted opacity-50 mb-3"></i>
                                    <h5>No products in this category</h5>
                                    <p class="text-muted mb-3">Start adding products to this category.</p>
                                    <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-primary-modern">
                                        <i class="fas fa-plus me-2"></i>
                                        Add First Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="modern-card animate-fade-in animate-delay-2">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-cogs text-primary me-2"></i>
                                Category Actions
                            </h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Category
                                </a>
                                <a href="{{ route('products.index') }}?category={{ $category->id }}" class="btn btn-outline-info">
                                    <i class="fas fa-filter me-2"></i>
                                    View All Products
                                </a>
                                <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-outline-success">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Product
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete this category? All products will be uncategorized.')">
                                        <i class="fas fa-trash me-2"></i>
                                        Delete Category
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-table {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.modern-table table {
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table th {
    background: #f8fafc;
    border: none;
    font-weight: 600;
    color: #334155;
    font-size: 14px;
    padding: 16px;
}

.modern-table td {
    border: none;
    border-top: 1px solid #e2e8f0;
    padding: 16px;
    vertical-align: middle;
}

.modern-table tbody tr:hover {
    background-color: #f8fafc;
}

.font-monospace {
    font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
}
</style>
@endsection 