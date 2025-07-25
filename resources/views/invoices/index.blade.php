@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Invoices Management</h1>
            <p class="text-muted">Manage customer invoices and track payments</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-modern" onclick="refreshPage()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary-modern">
                <i class="fas fa-plus me-2"></i>Create Invoice
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Invoices</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-file-invoice text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Paid</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['paid'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-check text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['overdue'] }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-danger">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="modern-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search invoices..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="customer">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary-modern w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="modern-card">
        <div class="card-header card-gradient d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="fas fa-file-invoice-dollar me-2"></i>Invoices List
            </h5>
            <span class="badge bg-white text-primary">{{ $invoices->total() }} Total</span>
        </div>
        <div class="card-body p-0">
            <div class="modern-table">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="customer-avatar me-2">
                                            {{ substr($invoice->customer->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $invoice->customer->name }}</div>
                                            <small class="text-muted">{{ $invoice->customer->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $invoice->title }}</div>
                                    <small class="text-muted">{{ $invoice->items->count() }} items</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">${{ number_format($invoice->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'sent' => 'warning',
                                            'paid' => 'success',
                                            'overdue' => 'danger',
                                            'cancelled' => 'dark'
                                        ];
                                        $color = $statusColors[$invoice->status] ?? 'secondary';
                                    @endphp
                                    <span class="status-badge status-{{ $color }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($invoice->due_date)
                                        <div class="fw-bold {{ $invoice->isOverdue() ? 'text-danger' : '' }}">
                                            {{ $invoice->due_date->format('M d, Y') }}
                                        </div>
                                        @if($invoice->isOverdue())
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">No due date</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $invoice->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $invoice->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('invoices.show', $invoice) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($invoice->status === 'draft')
                                            <a href="{{ route('invoices.edit', $invoice) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete({{ $invoice->id }}, '{{ $invoice->invoice_number }}')" 
                                                title="Delete" {{ $invoice->status !== 'draft' ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-invoice fa-3x mb-3"></i>
                                        <p>No invoices found.</p>
                                        <a href="{{ route('invoices.create') }}" class="btn btn-primary-modern">
                                            <i class="fas fa-plus me-2"></i>Create First Invoice
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($invoices->hasPages())
            <div class="card-footer">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete invoice <strong id="invoiceNumber"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
    text-transform: uppercase;
}
</style>

<script>
// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Refresh page
function refreshPage() {
    window.location.reload();
}

// Delete confirmation
function confirmDelete(invoiceId, invoiceNumber) {
    document.getElementById('invoiceNumber').textContent = invoiceNumber;
    document.getElementById('deleteForm').action = `/invoices/${invoiceId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Export placeholder
function exportData() {
    alert('Export functionality will be implemented soon!');
}
</script>
@endsection 