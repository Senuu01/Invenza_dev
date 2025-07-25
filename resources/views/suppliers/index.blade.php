@extends('layouts.app')

@section('header', 'Suppliers Management')

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
                                <i class="fas fa-truck text-primary me-2"></i>
                                Suppliers Management
                            </h4>
                            <p class="text-muted mb-0">Manage your supplier relationships and partnerships</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button onclick="refreshPage()" class="btn btn-outline-modern">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh
                            </button>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-plus me-2"></i>
                                Add Supplier
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
                                <i class="fas fa-truck text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $suppliers->total() }}</div>
                            <div class="text-muted small">Total Suppliers</div>
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
                            <div class="fw-bold text-dark fs-4">{{ $suppliers->where('status', 'active')->count() }}</div>
                            <div class="text-muted small">Active Suppliers</div>
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
                                <i class="fas fa-boxes text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $suppliers->sum('products_count') ?? 0 }}</div>
                            <div class="text-muted small">Products Supplied</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-handshake text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $suppliers->where('created_at', '>=', now()->subMonth())->count() }}</div>
                            <div class="text-muted small">New This Month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('suppliers.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Search suppliers..." 
                                       name="search" value="{{ request('search') }}" id="searchSuppliers">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-modern flex-fill">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-2">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-1 fw-bold text-dark">
                            <i class="fas fa-list text-primary me-2"></i>
                            Suppliers List
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-modern btn-sm" onclick="exportSuppliers()">
                                <i class="fas fa-download me-2"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">{{ $suppliers->total() }} suppliers found</p>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Contact Info</th>
                                    <th>Location</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-3 p-2" style="background: {{ $supplier->status === 'active' ? 'linear-gradient(135deg, #3b82f6, #1d4ed8)' : 'linear-gradient(135deg, #6b7280, #4b5563)' }};">
                                                    <i class="fas fa-building text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $supplier->name }}</div>
                                                <div class="small text-muted">
                                                    {{ $supplier->products_count ? $supplier->products_count . ' products' : 'No products' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="small">
                                                <i class="fas fa-envelope me-1 text-muted"></i>
                                                {{ $supplier->email }}
                                            </div>
                                            @if($supplier->phone)
                                            <div class="small text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                {{ $supplier->phone }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            {{ $supplier->address }}<br>
                                            @if($supplier->city || $supplier->state)
                                                {{ $supplier->city }}{{ $supplier->city && $supplier->state ? ', ' : '' }}{{ $supplier->state }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($supplier->products_count > 0)
                                            <span class="badge bg-primary bg-opacity-10 text-primary">{{ $supplier->products_count }} products</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">No products</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $supplier->status === 'active' ? 'status-success' : 'status-danger' }}">
                                            {{ ucfirst($supplier->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                            <a href="{{ route('stock-requests.create') }}?supplier_id={{ $supplier->id }}" 
                                               class="btn btn-sm btn-outline-success" title="Request Stock">
                                                <i class="fas fa-truck-loading"></i>
                                            </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" 
                                                    onclick="confirmDelete({{ $supplier->id }}, '{{ $supplier->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fs-2 mb-3 d-block"></i>
                                            <h5>No suppliers found</h5>
                                            <p class="mb-0">Get started by adding your first supplier.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($suppliers->hasPages())
                <div class="card-footer bg-white border-0 px-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} suppliers
                        </div>
                        <div>
                            {{ $suppliers->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
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
                <p class="mb-1">Are you sure you want to delete this supplier?</p>
                <p class="text-muted small mb-3" id="supplierName"></p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    This action cannot be undone. If this supplier has associated products, the deletion will fail.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Delete Supplier
                    </button>
                </form>
            </div>
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

.input-group-text {
    border: 2px solid #e2e8f0;
    border-right: none;
    background: #f8fafc;
    font-weight: 600;
}

.form-control:focus + .input-group-text,
.input-group .form-control:focus {
    border-color: #3b82f6;
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-content {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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

// Search functionality
document.getElementById('searchSuppliers').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const supplierName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(2) .small').textContent.toLowerCase();
        
        if (supplierName.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Status filter functionality
document.getElementById('statusFilter').addEventListener('change', function(e) {
    const filterValue = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const status = row.querySelector('.status-badge').textContent.toLowerCase();
        
        if (filterValue === '' || status.includes(filterValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Delete confirmation
function confirmDelete(supplierId, supplierName) {
    document.getElementById('supplierName').textContent = supplierName;
    document.getElementById('deleteForm').action = `/suppliers/${supplierId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Export functionality
function exportSuppliers() {
    // This would typically make an AJAX request to export data
    alert('Export functionality would be implemented here');
}
</script>
@endsection 