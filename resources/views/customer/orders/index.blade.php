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

    <!-- Redirect to main orders page -->
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
                        <h4 class="fw-bold text-dark mb-3">Order Management</h4>
                        <p class="text-muted mb-4 fs-5">View your complete order history and track your purchases.</p>
                        <a href="{{ route('orders.index') }}" class="btn btn-primary-modern">
                            <i class="fas fa-list me-2"></i>
                            View All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</style>
@endsection 