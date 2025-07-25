@extends('layouts.app')

@section('header', 'Stock Requests')

@section('content')
<div class="container-fluid px-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-truck-loading text-primary me-2"></i>
                        Stock Requests Management
                    </h6>
                    @if(auth()->user()->isStaff())
                    <a href="{{ route('stock-requests.create') }}" class="btn btn-primary-modern">
                        <i class="fas fa-plus me-2"></i>
                        New Stock Request
                    </a>
                    @endif
                </div>
                <div class="card-body p-4">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Status</label>
                            <select class="form-select modern-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Supplier</label>
                            <select class="form-select modern-select" id="supplierFilter">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Product</label>
                            <select class="form-select modern-select" id="productFilter">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3 d-flex align-items-end">
                            <button class="btn btn-outline-modern w-100" onclick="applyFilters()">
                                <i class="fas fa-filter me-2"></i>
                                Apply Filters
                            </button>
                        </div>
                    </div>

                    <!-- Stock Requests Table -->
                    <div class="modern-table">
                        @if($stockRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-semibold text-dark">ID</th>
                                            <th class="fw-semibold text-dark">Product</th>
                                            <th class="fw-semibold text-dark">Supplier</th>
                                            <th class="fw-semibold text-dark">Quantity</th>
                                            <th class="fw-semibold text-dark">Requested By</th>
                                            <th class="fw-semibold text-dark">Status</th>
                                            <th class="fw-semibold text-dark">Date</th>
                                            <th class="fw-semibold text-dark">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stockRequests as $request)
                                            <tr class="table-row-hover">
                                                <td>
                                                    <span class="badge bg-light text-dark fw-semibold">#{{ $request->id }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="product-icon me-3">
                                                            <i class="fas fa-box text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $request->product->name }}</div>
                                                            <div class="small text-muted">SKU: {{ $request->product->sku }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="supplier-icon me-2">
                                                            <i class="fas fa-truck text-success"></i>
                                                        </div>
                                                        <span class="fw-semibold">{{ $request->supplier->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold text-primary fs-6">{{ $request->quantity_requested }}</span>
                                                        @if($request->product->quantity <= 10)
                                                            <div class="ms-2">
                                                                <span class="badge bg-danger small">Low stock: {{ $request->product->quantity }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar me-2">
                                                            {{ strtoupper(substr($request->requestedBy->name, 0, 1)) }}
                                                        </div>
                                                        <span class="fw-semibold">{{ $request->requestedBy->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {!! $request->status_badge !!}
                                                </td>
                                                <td>
                                                    <div class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $request->created_at->format('M d, Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('stock-requests.show', $request) }}" 
                                                           class="btn btn-sm btn-outline-modern" 
                                                           title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($request->status === 'pending')
                                                            @if(auth()->user()->isAdmin())
                                                                <button class="btn btn-sm btn-success" 
                                                                        onclick="approveRequest({{ $request->id }})"
                                                                        title="Approve Request">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger" 
                                                                        onclick="rejectRequest({{ $request->id }})"
                                                                        title="Reject Request">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            @elseif(auth()->user()->isStaff() && $request->requested_by === auth()->id())
                                                                <a href="{{ route('stock-requests.edit', $request) }}" 
                                                                   class="btn btn-sm btn-outline-modern"
                                                                   title="Edit Request">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endif
                                                        @elseif($request->status === 'approved' && auth()->user()->isAdmin())
                                                            <button class="btn btn-sm btn-info" 
                                                                    onclick="completeRequest({{ $request->id }})"
                                                                    title="Complete Request">
                                                                <i class="fas fa-check-double"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $stockRequests->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">No stock requests found</h5>
                                    <p class="text-muted mb-4">
                                        @if(auth()->user()->isStaff())
                                            Create your first stock request to get started.
                                        @else
                                            Staff members will create stock requests here.
                                        @endif
                                    </p>
                                    @if(auth()->user()->isStaff())
                                        <a href="{{ route('stock-requests.create') }}" class="btn btn-primary-modern">
                                            <i class="fas fa-plus me-2"></i>
                                            Create Stock Request
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Approve Stock Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm">
                <div class="modal-body">
                    <input type="hidden" id="requestId" name="request_id">
                    <input type="hidden" id="actionType" name="action_type">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Notes (Optional)</label>
                        <textarea class="form-control modern-textarea" id="adminNotes" name="admin_notes" rows="3" 
                                  placeholder="Add any notes about this decision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-modern" id="submitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern styling for stock requests page */
.modern-select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    background-color: #fff;
}

.modern-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.modern-textarea {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    resize: vertical;
}

.modern-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.modern-modal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-icon, .supplier-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(59, 130, 246, 0.1);
}

.supplier-icon {
    background: rgba(34, 197, 94, 0.1);
}

.user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
}

.empty-state i {
    font-size: 4rem;
    opacity: 0.5;
}

.btn-group .btn {
    margin-right: 2px;
    border-radius: 6px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Status badge styling */
.badge {
    padding: 0.5em 0.75em;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
}

.badge.bg-warning {
    background-color: #fbbf24 !important;
    color: #92400e !important;
}

.badge.bg-success {
    background-color: #34d399 !important;
    color: #065f46 !important;
}

.badge.bg-danger {
    background-color: #f87171 !important;
    color: #991b1b !important;
}

.badge.bg-info {
    background-color: #60a5fa !important;
    color: #1e40af !important;
}

/* Animation for table rows */
.table tbody tr {
    transition: all 0.3s ease;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<style>
/* Modern styling for stock requests page */
.modern-select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    background-color: #fff;
}

.modern-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.modern-textarea {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    resize: vertical;
}

.modern-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.modern-modal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-icon, .supplier-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(59, 130, 246, 0.1);
}

.supplier-icon {
    background: rgba(34, 197, 94, 0.1);
}

.user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
}

.empty-state i {
    font-size: 4rem;
    opacity: 0.5;
}

.btn-group .btn {
    margin-right: 2px;
    border-radius: 6px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Status badge styling */
.badge {
    padding: 0.5em 0.75em;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
}

.badge.bg-warning {
    background-color: #fbbf24 !important;
    color: #92400e !important;
}

.badge.bg-success {
    background-color: #34d399 !important;
    color: #065f46 !important;
}

.badge.bg-danger {
    background-color: #f87171 !important;
    color: #991b1b !important;
}

.badge.bg-info {
    background-color: #60a5fa !important;
    color: #1e40af !important;
}

/* Animation for table rows */
.table tbody tr {
    transition: all 0.3s ease;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<script>
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const supplier = document.getElementById('supplierFilter').value;
    const product = document.getElementById('productFilter').value;
    
    let url = new URL(window.location);
    if (status) url.searchParams.set('status', status);
    else url.searchParams.delete('status');
    
    if (supplier) url.searchParams.set('supplier', supplier);
    else url.searchParams.delete('supplier');
    
    if (product) url.searchParams.set('product', product);
    else url.searchParams.delete('product');
    
    window.location.href = url.toString();
}

function approveRequest(requestId) {
    document.getElementById('modalTitle').textContent = 'Approve Stock Request';
    document.getElementById('requestId').value = requestId;
    document.getElementById('actionType').value = 'approve';
    document.getElementById('adminNotes').placeholder = 'Add any notes about this approval...';
    document.getElementById('submitBtn').textContent = 'Approve';
    document.getElementById('submitBtn').className = 'btn btn-success';
    
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function rejectRequest(requestId) {
    document.getElementById('modalTitle').textContent = 'Reject Stock Request';
    document.getElementById('requestId').value = requestId;
    document.getElementById('actionType').value = 'reject';
    document.getElementById('adminNotes').placeholder = 'Please provide a reason for rejection...';
    document.getElementById('adminNotes').required = true;
    document.getElementById('submitBtn').textContent = 'Reject';
    document.getElementById('submitBtn').className = 'btn btn-danger';
    
    new bootstrap.Modal(document.getElementById('approvalModal')).show();
}

function completeRequest(requestId) {
    if (confirm('Are you sure you want to complete this stock request? This will update the product inventory.')) {
        fetch(`/stock-requests/${requestId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error completing request: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error completing request');
        });
    }
}

document.getElementById('approvalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const requestId = document.getElementById('requestId').value;
    const actionType = document.getElementById('actionType').value;
    const adminNotes = document.getElementById('adminNotes').value;
    
    const url = actionType === 'approve' 
        ? `/stock-requests/${requestId}/approve`
        : `/stock-requests/${requestId}/reject`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            admin_notes: adminNotes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error processing request: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error processing request');
    });
});

// Set filter values from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('status')) {
        document.getElementById('statusFilter').value = urlParams.get('status');
    }
    if (urlParams.get('supplier')) {
        document.getElementById('supplierFilter').value = urlParams.get('supplier');
    }
    if (urlParams.get('product')) {
        document.getElementById('productFilter').value = urlParams.get('product');
    }
});
</script>
@endsection 