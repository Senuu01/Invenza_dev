@extends('layouts.app')

@section('header', 'My Profile')

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
                                <i class="fas fa-user text-primary me-2"></i>
                                My Profile
                            </h4>
                            <p class="text-muted mb-0">Manage your account information</p>
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

    <!-- Profile Information -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-user-circle me-2"></i>
                        Account Information
                    </h5>
                    
                    <div class="profile-info">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Name</label>
                                <p class="form-control-plaintext">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Email</label>
                                <p class="form-control-plaintext">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Account Type</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary">Customer</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Member Since</label>
                                <p class="form-control-plaintext">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-actions mt-4">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary-modern">
                            <i class="fas fa-edit me-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h5>
                    
                    <div class="quick-actions">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-modern w-100 mb-3">
                            <i class="fas fa-shopping-bag me-2"></i>
                            View Orders
                        </a>
                        
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-modern w-100 mb-3">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Shopping Cart
                        </a>
                        
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern w-100">
                            <i class="fas fa-store me-2"></i>
                            Continue Shopping
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

/* Profile Info Styling */
.profile-info .form-label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.profile-info .form-control-plaintext {
    font-size: 16px;
    color: #1e293b;
    padding: 0;
    margin: 0;
    border: none;
    background: transparent;
}

/* Quick Actions */
.quick-actions .btn {
    text-align: left;
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.quick-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
    .profile-info .row {
        margin-bottom: 1rem;
    }
    
    .profile-info .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection 