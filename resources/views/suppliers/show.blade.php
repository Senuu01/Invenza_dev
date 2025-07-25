@extends('layouts.app')

@section('header', 'Supplier Details')

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
                                <i class="fas fa-building text-primary me-2"></i>
                                {{ $supplier->name }}
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="status-badge {{ $supplier->status === 'active' ? 'status-success' : 'status-danger' }} me-2">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                                Supplier Details & Information
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary-modern">
                                <i class="fas fa-edit me-2"></i>
                                Edit Supplier
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to List
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

    <!-- Supplier Information -->
    <div class="row mb-3">
        <!-- Basic Details -->
        <div class="col-lg-8 mb-3">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h5 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Supplier Information
                    </h5>
                    <p class="text-muted small mb-0">Complete supplier details and contact information</p>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item mb-3">
                                <div class="detail-label">Company Name</div>
                                <div class="detail-value">{{ $supplier->name }}</div>
                            </div>
                            <div class="detail-item mb-3">
                                <div class="detail-label">Email Address</div>
                                <div class="detail-value">
                                    <a href="mailto:{{ $supplier->email }}" class="text-primary text-decoration-none">
                                        <i class="fas fa-envelope me-2"></i>
                                        {{ $supplier->email }}
                                    </a>
                                </div>
                            </div>
                            <div class="detail-item mb-3">
                                <div class="detail-label">Phone Number</div>
                                <div class="detail-value">
                                    @if($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}" class="text-primary text-decoration-none">
                                            <i class="fas fa-phone me-2"></i>
                                            {{ $supplier->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item mb-3">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    <span class="status-badge {{ $supplier->status === 'active' ? 'status-success' : 'status-danger' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="detail-item mb-3">
                                <div class="detail-label">Products Count</div>
                                <div class="detail-value">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fs-6">
                                        {{ $supplier->products_count ?? $supplier->products->count() }} products
                                    </span>
                                </div>
                            </div>
                            <div class="detail-item mb-3">
                                <div class="detail-label">Member Since</div>
                                <div class="detail-value">{{ $supplier->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="border-top pt-3 mt-3">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            Address Information
                        </h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="detail-item mb-2">
                                    <div class="detail-label">Street Address</div>
                                    <div class="detail-value">{{ $supplier->address ?: 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-item mb-2">
                                    <div class="detail-label">City</div>
                                    <div class="detail-value">{{ $supplier->city ?: 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <div class="detail-label">State/Province</div>
                                    <div class="detail-value">{{ $supplier->state ?: 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item mb-2">
                                    <div class="detail-label">Country</div>
                                    <div class="detail-value">{{ $supplier->country ?: 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-3">
            <div class="modern-card animate-fade-in animate-delay-2">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h5 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Quick Actions
                    </h5>
                    <p class="text-muted small mb-0">Manage this supplier</p>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary-modern">
                            <i class="fas fa-edit me-2"></i>
                            Edit Supplier
                        </a>
                        
                        <button type="button" class="btn btn-outline-success" onclick="contactSupplier()">
                            <i class="fas fa-envelope me-2"></i>
                            Send Email
                        </button>
                        
                        @if($supplier->phone)
                        <a href="tel:{{ $supplier->phone }}" class="btn btn-outline-info">
                            <i class="fas fa-phone me-2"></i>
                            Call Supplier
                        </a>
                        @endif
                        
                        <button type="button" class="btn btn-outline-warning" onclick="exportSupplierData()">
                            <i class="fas fa-download me-2"></i>
                            Export Data
                        </button>
                        
                        <hr class="my-2">
                        
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>
                            Delete Supplier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Products -->
    @if($supplier->products && $supplier->products->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-3">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-boxes text-primary me-2"></i>
                                Associated Products
                            </h5>
                            <p class="text-muted small mb-0">{{ $supplier->products->count() }} products from this supplier</p>
                        </div>
                        <a href="{{ route('products.index', ['supplier' => $supplier->id]) }}" class="btn btn-outline-modern btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>
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
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->products->take(10) as $product)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                        @if($product->brand)
                                            <div class="small text-muted">{{ $product->brand }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $product->sku }}</code>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">${{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <span class="status-badge 
                                            @if($product->status === 'in_stock') status-success
                                            @elseif($product->status === 'low_stock') status-warning
                                            @else status-danger
                                            @endif">
                                            {{ str_replace('_', ' ', ucfirst($product->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="View Product">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($supplier->products->count() > 10)
                <div class="card-footer bg-white border-0 px-4 pb-3">
                    <div class="text-center">
                        <a href="{{ route('products.index', ['supplier' => $supplier->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>
                            View All {{ $supplier->products->count() }} Products
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-3">
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-box-open fs-1 mb-3 d-block"></i>
                        <h5>No Products Found</h5>
                        <p class="mb-3">This supplier doesn't have any products associated yet.</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary-modern">
                            <i class="fas fa-plus me-2"></i>
                            Add Product for this Supplier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modern-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Are you sure you want to delete <strong>{{ $supplier->name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    This action cannot be undone. 
                    @if($supplier->products && $supplier->products->count() > 0)
                        This supplier has {{ $supplier->products->count() }} associated products and cannot be deleted.
                    @else
                        This supplier will be permanently removed from the system.
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                @if(!$supplier->products || $supplier->products->count() === 0)
                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Delete Supplier
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.detail-item {
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 8px;
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 1rem;
    color: #1e293b;
    font-weight: 500;
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-content {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

code {
    font-size: 0.875rem;
    font-family: 'Courier New', monospace;
}
</style>

<script>
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

// Delete confirmation
function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Contact supplier
function contactSupplier() {
    const email = '{{ $supplier->email }}';
    const subject = 'Inquiry from {{ config("app.name") }}';
    const body = 'Hello {{ $supplier->name }},\n\nI hope this email finds you well.\n\nBest regards';
    
    window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
}

// Export supplier data
function exportSupplierData() {
    // This would typically make an AJAX request to export data
    alert('Export functionality would be implemented here');
}
</script>
@endsection 