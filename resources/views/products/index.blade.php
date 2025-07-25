@extends('layouts.app')

@section('header', 'Products Management')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card card-gradient animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-box text-primary me-2"></i>
                                Products Management
                            </h4>
                            <p class="text-muted mb-0">Manage your product inventory</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button onclick="refreshPage()" class="btn btn-outline-modern">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh
                            </button>
                            <a href="{{ route('products.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-plus me-2"></i>
                                Add Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show modern-card" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show modern-card" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                <i class="fas fa-boxes text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $products->total() }}</div>
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
                                <i class="fas fa-check-circle text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\Product::where('status', 'in_stock')->count() }}</div>
                            <div class="text-muted small">In Stock</div>
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
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\Product::where('status', 'low_stock')->count() }}</div>
                            <div class="text-muted small">Low Stock</div>
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
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\Product::where('status', 'out_of_stock')->count() }}</div>
                            <div class="text-muted small">Out of Stock</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('products.index') }}" class="row g-3" id="searchForm">
                        <div class="col-md-4">
                            <div class="modern-search">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search products..." class="form-control"
                                       id="searchInput">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-modern flex-fill">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request()->hasAny(['search', 'category', 'status']))
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-modern">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-list text-primary me-2"></i>
                                Products List
                            </h6>
                            <p class="text-muted small mb-0">{{ $products->total() }} products found</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-3 p-2" style="background: {{ $product->category->color ?? '#6b7280' }}20;">
                                                    <i class="fas fa-box" style="color: {{ $product->category->color ?? '#6b7280' }};"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                                <div class="small text-muted">SKU: {{ $product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge rounded-pill" style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }};">
                                                <i class="{{ $product->category->icon ?? 'fas fa-tag' }} me-1"></i>
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->supplier)
                                            <div class="small">
                                                <div class="fw-semibold">{{ $product->supplier->name }}</div>
                                                <div class="text-muted">{{ $product->supplier->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">No supplier</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $product->quantity <= 10 ? 'text-danger' : ($product->quantity <= 20 ? 'text-warning' : 'text-success') }}">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->status == 'in_stock')
                                            <span class="status-badge status-success">In Stock</span>
                                        @elseif($product->status == 'low_stock')
                                            <span class="status-badge status-warning">Low Stock</span>
                                        @else
                                            <span class="status-badge status-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
                                                <button type="button" class="btn btn-sm btn-outline-warning" title="Update Stock" 
                                                        onclick="openStockModal({{ $product->id }}, '{{ $product->name }}', {{ $product->quantity }})">
                                                    <i class="fas fa-boxes"></i>
                                                </button>
                                            @endif
                                            
                                            @if(auth()->user()->isAdmin())
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-box-open fs-1 mb-3 opacity-50"></i>
                                            <h5>No products found</h5>
                                            <p class="mb-3">{{ request()->hasAny(['search', 'category', 'status']) ? 'Try adjusting your search criteria.' : 'Get started by adding your first product.' }}</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary-modern">
                                                <i class="fas fa-plus me-2"></i>
                                                Add Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($products->hasPages())
                <div class="card-footer bg-white border-0 px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted small">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                        </div>
                        <div>
                            {{ $products->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="stockModalLabel">
                    <i class="fas fa-boxes text-warning me-2"></i>
                    Update Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="stockUpdateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body px-4 py-3">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background: #f8fafc;">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-3 p-2" style="background: #fbbf24; color: white;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" id="modalProductName"></div>
                                    <div class="small text-muted">Current Stock: <span id="modalCurrentStock" class="fw-bold"></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="quantity" class="form-label fw-semibold">
                                <i class="fas fa-hashtag me-1"></i>
                                New Quantity
                            </label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
                            <div class="form-text">Enter the updated stock quantity</div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label fw-semibold">
                                <i class="fas fa-sticky-note me-1"></i>
                                Notes (Optional)
                            </label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this stock update..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius);
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

/* Table styling for status badges */
.modern-table table td {
    vertical-align: middle;
    padding: 16px 12px;
}

.modern-table table th {
    vertical-align: middle;
    padding: 16px 12px;
    font-weight: 600;
    color: #374151;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

/* Ensure status badges don't overflow */
.modern-table .status-badge {
    margin: 0;
    vertical-align: middle;
}

.pagination .page-link {
    border: none;
    color: #6b7280;
    font-weight: 500;
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: var(--border-radius);
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
</style>

<script>
let searchTimeout;

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Refresh functionality
function refreshPage() {
    const refreshBtn = document.querySelector('[onclick="refreshPage()"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Debounced search
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
});

// Stock update modal functionality
function openStockModal(productId, productName, currentStock) {
    // Set product information
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('modalCurrentStock').textContent = currentStock;
    
    // Set form action URL
    const form = document.getElementById('stockUpdateForm');
    form.action = `/products/${productId}/stock`;
    
    // Set current quantity as default
    document.getElementById('quantity').value = currentStock;
    
    // Clear notes
    document.getElementById('notes').value = '';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('stockModal'));
    modal.show();
}

// Handle stock update form submission
document.getElementById('stockUpdateForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;
    
    // Create FormData object
    const formData = new FormData(form);
    
    // Send AJAX request
    fetch(form.action, {
        method: 'PATCH',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('stockModal'));
            modal.hide();
            
            // Show success message
            showAlert('success', data.message || 'Stock updated successfully!');
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('error', data.message || 'Failed to update stock');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating stock');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
    });
});

// Helper function to show alerts
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show modern-card" role="alert">
            <i class="${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert alert after the header
    const header = document.querySelector('.container-fluid .row:first-child');
    if (header) {
        header.insertAdjacentHTML('afterend', `<div class="row mb-3"><div class="col-12">${alertHtml}</div></div>`);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
}
</script>
@endsection