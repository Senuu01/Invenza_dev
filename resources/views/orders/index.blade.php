@extends('layouts.app')

@section('header', 'My Orders')

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
                                <i class="fas fa-shopping-bag text-primary me-2"></i>
                                My Orders
                            </h4>
                            <p class="text-muted mb-0">View and track your order history</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Store
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

    @if($orders->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="modern-card animate-fade-in">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->order_number }}</strong>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{!! $order->status_badge !!}</td>
                                            <td>{!! $order->payment_status_badge !!}</td>
                                            <td>
                                                <strong class="text-success">${{ number_format($order->total, 2) }}</strong>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('orders.show', $order) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($order->status === 'pending')
                                                        <form action="{{ route('orders.cancel', $order) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <div class="empty-state-icon mb-4">
                                <div class="icon-circle">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-dark mb-3">No Orders Yet</h4>
                            <p class="text-muted mb-4 fs-5">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary-modern">
                                <i class="fas fa-shopping-bag me-2"></i>
                                Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Modern Card Styling */
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-gradient h4,
.card-gradient p {
    color: white !important;
}

/* Table Styling */
.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    border-bottom: 2px solid #e2e8f0;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    border-top: 1px solid #f1f5f9;
    vertical-align: middle;
    padding: 16px 12px;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

/* Badge Styling */
.badge {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 8px;
}

/* Button Group */
.btn-group .btn {
    margin-right: 4px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
    max-width: 400px;
    margin: 0 auto;
}

.empty-state-icon {
    margin-bottom: 1.5rem;
}

.icon-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.icon-circle i {
    font-size: 40px;
    color: white;
}

/* Animation */
.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
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

/* Responsive Design */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 14px;
    }
    
    .btn-group .btn {
        padding: 4px 8px;
        font-size: 12px;
    }
}
</style>
@endsection 