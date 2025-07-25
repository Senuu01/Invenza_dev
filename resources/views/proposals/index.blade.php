@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Proposals Management</h1>
            <p class="text-muted">Create and manage customer proposals</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-modern" onclick="refreshPage()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <a href="{{ route('proposals.create') }}" class="btn btn-primary-modern">
                <i class="fas fa-plus me-2"></i>Create Proposal
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Proposals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proposals->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-file-contract text-white"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Draft</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proposals->where('status', 'draft')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info">
                                <i class="fas fa-edit text-white"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proposals->where('status', 'sent')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-paper-plane text-white"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Accepted</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proposals->where('status', 'accepted')->count() }}</div>
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
    </div>

    <!-- Search and Filters -->
    <div class="modern-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('proposals.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search proposals..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
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

    <!-- Proposals Table -->
    <div class="modern-card">
        <div class="card-header card-gradient d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="fas fa-file-contract me-2"></i>Proposals List
            </h5>
            <span class="badge bg-white text-primary">{{ $proposals->total() }} Total</span>
        </div>
        <div class="card-body p-0">
            <div class="modern-table">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Proposal #</th>
                            <th>Customer</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Valid Until</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $proposal)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $proposal->proposal_number }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="customer-avatar me-2">
                                            {{ substr($proposal->customer->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $proposal->customer->name }}</div>
                                            <small class="text-muted">{{ $proposal->customer->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $proposal->title }}</div>
                                    <small class="text-muted">{{ $proposal->items->count() }} items</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">${{ number_format($proposal->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'sent' => 'warning',
                                            'accepted' => 'success',
                                            'rejected' => 'danger',
                                            'expired' => 'dark'
                                        ];
                                        $color = $statusColors[$proposal->status] ?? 'secondary';
                                    @endphp
                                    <span class="status-badge status-{{ $color }}">
                                        {{ ucfirst($proposal->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($proposal->valid_until)
                                        <div class="fw-bold {{ $proposal->isExpired() ? 'text-danger' : '' }}">
                                            {{ $proposal->valid_until->format('M d, Y') }}
                                        </div>
                                        @if($proposal->isExpired())
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Expired
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">No expiry</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $proposal->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $proposal->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('proposals.show', $proposal) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($proposal->status === 'draft')
                                            <a href="{{ route('proposals.edit', $proposal) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="More Actions">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($proposal->status === 'draft')
                                                    <li>
                                                        <form action="{{ route('proposals.send', $proposal) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-paper-plane me-2"></i>Send to Customer
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($proposal->status === 'accepted')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('proposals.convert-to-invoice', $proposal) }}">
                                                            <i class="fas fa-file-invoice me-2"></i>Convert to Invoice
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('proposals.pdf', $proposal) }}">
                                                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('proposals.duplicate', $proposal) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-copy me-2"></i>Duplicate
                                                        </button>
                                                    </form>
                                                </li>
                                                @if($proposal->status === 'draft')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" 
                                                                onclick="confirmDelete({{ $proposal->id }}, '{{ $proposal->proposal_number }}')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-file-contract fa-3x mb-3"></i>
                                        <p>No proposals found.</p>
                                        <a href="{{ route('proposals.create') }}" class="btn btn-primary-modern">
                                            <i class="fas fa-plus me-2"></i>Create First Proposal
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($proposals->hasPages())
            <div class="card-footer">
                {{ $proposals->links() }}
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
                <p>Are you sure you want to delete proposal <strong id="proposalNumber"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Proposal</button>
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
function confirmDelete(proposalId, proposalNumber) {
    document.getElementById('proposalNumber').textContent = proposalNumber;
    document.getElementById('deleteForm').action = `/proposals/${proposalId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Export placeholder
function exportData() {
    alert('Export functionality will be implemented soon!');
}
</script>
@endsection 