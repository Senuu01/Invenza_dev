@extends('layouts.app')

@section('header', 'Customers Management')

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
                                <i class="fas fa-users text-primary me-2"></i>
                                Customers Management
                            </h4>
                            <p class="text-muted mb-0">Manage your customer relationships and information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button onclick="refreshPage()" class="btn btn-outline-modern">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh
                            </button>
                            <a href="{{ route('customers.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-user-plus me-2"></i>
                                Add Customer
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
                                <i class="fas fa-users text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $customers->total() ?? 0 }}</div>
                            <div class="text-muted small">Total Customers</div>
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
                                <i class="fas fa-user-check text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $customers->where('status', 'active')->count() ?? 0 }}</div>
                            <div class="text-muted small">Active Customers</div>
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
                                <i class="fas fa-credit-card text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">${{ number_format($customers->sum('credit_limit') ?? 0, 0) }}</div>
                            <div class="text-muted small">Total Credit Limit</div>
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
                                <i class="fas fa-user-plus text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $customers->where('created_at', '>=', now()->subMonth())->count() ?? 0 }}</div>
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
                    <form method="GET" action="{{ route('customers.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Search customers by name, email, or phone..." 
                                       name="search" value="{{ request('search') }}" id="searchCustomers">
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
                                <a href="{{ route('customers.index') }}" class="btn btn-outline-modern">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-2">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-1 fw-bold text-dark">
                            <i class="fas fa-list text-primary me-2"></i>
                            Customers List
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-modern btn-sm" onclick="exportCustomers()">
                                <i class="fas fa-download me-2"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">{{ $customers->total() ?? 0 }} customers found</p>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Contact Info</th>
                                    <th>Location</th>
                                    <th>Credit Info</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="customer-avatar">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $customer->name }}</div>
                                                <div class="small text-muted">Customer since {{ $customer->created_at->format('M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="small">
                                                <i class="fas fa-envelope me-1 text-muted"></i>
                                                {{ $customer->email }}
                                            </div>
                                            @if($customer->phone)
                                            <div class="small text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                {{ $customer->phone }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            @if($customer->city || $customer->country)
                                                {{ $customer->city }}{{ $customer->city && $customer->country ? ', ' : '' }}{{ $customer->country }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @if($customer->credit_limit)
                                                <div class="small">
                                                    <span class="text-muted">Limit:</span> 
                                                    <span class="fw-semibold">${{ number_format($customer->credit_limit, 0) }}</span>
                                                </div>
                                                <div class="small text-muted">
                                                    <span>Balance:</span> ${{ number_format($customer->current_balance ?? 0, 0) }}
                                                </div>
                                            @else
                                                <span class="text-muted small">No credit info</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $customer->status === 'active' ? 'status-success' : 'status-danger' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" 
                                                    onclick="confirmDelete({{ $customer->id }}, '{{ $customer->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-user-friends fs-2 mb-3 d-block"></i>
                                            <h5>No customers found</h5>
                                            <p class="mb-0">Get started by adding your first customer.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($customers->hasPages())
                <div class="card-footer bg-white border-0 px-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                        </div>
                        <div>
                            {{ $customers->links() }}
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
                <p class="mb-1">Are you sure you want to delete this customer?</p>
                <p class="text-muted small mb-3" id="customerName"></p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    This action cannot be undone. All customer data will be permanently removed.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Delete Customer
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

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-content {
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.table th {
    font-weight: 600;
    color: #374151;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
    padding: 16px 12px;
}

.table td {
    padding: 16px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.02);
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

// Delete confirmation
function confirmDelete(customerId, customerName) {
    document.getElementById('customerName').textContent = customerName;
    document.getElementById('deleteForm').action = route('customers.destroy', {id: customerId});
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Export functionality
function exportCustomers() {
    // This would typically make an AJAX request to export data
    alert('Export functionality would be implemented here');
}
</script>
@endsection