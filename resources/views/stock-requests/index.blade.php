@extends('layouts.app')

@section('header', 'Stock Requests Management')

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
                                <i class="fas fa-truck-loading text-primary me-2"></i>
                                Stock Requests Management
                            </h4>
                            <p class="text-muted mb-0">
                                @if(auth()->user()->isStaff())
                                    Request new stock from suppliers
                                @else
                                    Manage and approve stock requests from staff
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button onclick="refreshPage()" class="btn btn-outline-modern">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh
                            </button>
                            @if(auth()->user()->isStaff())
                            <a href="{{ route('stock-requests.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-plus me-2"></i>
                                New Stock Request
                            </a>
                            @endif
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
                                <i class="fas fa-clipboard-list text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $stockRequests->total() }}</div>
                            <div class="text-muted small">Total Requests</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-clock text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\StockRequest::where('status', 'pending')->count() }}</div>
                            <div class="text-muted small">Pending</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-check-circle text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\StockRequest::where('status', 'approved')->count() }}</div>
                            <div class="text-muted small">Approved</div>
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
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\StockRequest::where('status', 'rejected')->count() }}</div>
                            <div class="text-muted small">Rejected</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
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
                                    <div class="empty-state-icon mb-4">
                                        <div class="icon-circle">
                                            <i class="fas fa-truck-loading"></i>
                                        </div>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-3">No Stock Requests Found</h4>
                                    <p class="text-muted mb-4 fs-5">
                                        @if(auth()->user()->isStaff())
                                            Ready to request new stock? Start by creating your first stock request.
                                        @else
                                            Staff members will create stock requests here for your approval.
                                        @endif
                                    </p>
                                    @if(auth()->user()->isStaff())
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('stock-requests.create') }}" class="btn btn-create-request">
                                                <div class="btn-content">
                                                    <div class="btn-icon">
                                                        <i class="fas fa-plus"></i>
                                                    </div>
                                                    <div class="btn-text">
                                                        <span class="btn-title">Create Stock Request</span>
                                                        <span class="btn-subtitle">Request new inventory</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
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
<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modern-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="approvalModalLabel">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Approve Stock Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to approve this stock request?</p>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label fw-semibold">Admin Notes (Optional)</label>
                        <textarea class="form-control modern-textarea" id="admin_notes" name="admin_notes" rows="3" placeholder="Add any notes for the requester..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Approve Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modern-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger" id="rejectionModalLabel">
                    <i class="fas fa-times-circle me-2"></i>
                    Reject Stock Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to reject this stock request?</p>
                    <div class="mb-3">
                        <label for="rejection_notes" class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control modern-textarea" id="rejection_notes" name="admin_notes" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern styling for stock requests page */
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-gradient h4,
.card-gradient p {
    color: white !important;
}

.modern-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
}

.modern-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.modern-textarea {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s ease;
    resize: vertical;
}

.modern-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.table-row-hover:hover {
    background-color: rgba(59, 130, 246, 0.05);
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 12px;
}

.product-icon,
.supplier-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(59, 130, 246, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state {
    padding: 60px 20px;
    max-width: 500px;
    margin: 0 auto;
}

.empty-state-icon {
    margin-bottom: 2rem;
}

.icon-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.icon-circle:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
}

.icon-circle i {
    font-size: 48px;
    color: white;
}

/* Create Request Button Styling */
.btn-create-request {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none;
    border-radius: 16px;
    padding: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    min-width: 280px;
    position: relative;
}

.btn-create-request:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

.btn-create-request:active {
    transform: translateY(-1px);
}

.btn-content {
    display: flex;
    align-items: center;
    padding: 20px 24px;
    color: white;
    text-decoration: none;
}

.btn-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    transition: all 0.3s ease;
}

.btn-create-request:hover .btn-icon {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.btn-icon i {
    font-size: 20px;
    color: white;
}

.btn-text {
    text-align: left;
}

.btn-title {
    display: block;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.btn-subtitle {
    display: block;
    font-size: 12px;
    opacity: 0.8;
    font-weight: 400;
}

/* Animation classes */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-delay-1 {
    animation-delay: 0.1s;
}

.animate-delay-2 {
    animation-delay: 0.2s;
}

.animate-delay-3 {
    animation-delay: 0.3s;
}

.animate-delay-4 {
    animation-delay: 0.4s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
function refreshPage() {
    window.location.reload();
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const supplier = document.getElementById('supplierFilter').value;
    const product = document.getElementById('productFilter').value;
    
    let url = new URL(window.location);
    if (status) url.searchParams.set('status', status);
    if (supplier) url.searchParams.set('supplier', supplier);
    if (product) url.searchParams.set('product', product);
    
    window.location.href = url.toString();
}

function approveRequest(requestId) {
    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    const form = document.getElementById('approvalForm');
    form.action = `/stock-requests/${requestId}/approve`;
    modal.show();
}

function rejectRequest(requestId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    const form = document.getElementById('rejectionForm');
    form.action = `/stock-requests/${requestId}/reject`;
    modal.show();
}

function completeRequest(requestId) {
    if (confirm('Are you sure you want to complete this stock request? This will update the product inventory.')) {
        fetch(`/stock-requests/${requestId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
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