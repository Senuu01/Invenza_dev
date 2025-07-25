@extends('layouts.app')

@section('header', 'Checkout')

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
                                <i class="fas fa-credit-card text-primary me-2"></i>
                                Checkout
                            </h4>
                            <p class="text-muted mb-0">Complete your purchase securely</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8 mb-4">
                <div class="modern-card animate-fade-in">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-user me-2"></i>
                            Billing Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_first_name" class="form-label fw-bold">First Name *</label>
                                <input type="text" class="form-control" id="billing_first_name" name="billing_first_name" 
                                       value="{{ old('billing_first_name', Auth::user()->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_last_name" class="form-label fw-bold">Last Name *</label>
                                <input type="text" class="form-control" id="billing_last_name" name="billing_last_name" 
                                       value="{{ old('billing_last_name') }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_email" class="form-label fw-bold">Email *</label>
                                <input type="email" class="form-control" id="billing_email" name="billing_email" 
                                       value="{{ old('billing_email', Auth::user()->email ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_phone" class="form-label fw-bold">Phone</label>
                                <input type="tel" class="form-control" id="billing_phone" name="billing_phone" 
                                       value="{{ old('billing_phone') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_address" class="form-label fw-bold">Address *</label>
                            <input type="text" class="form-control" id="billing_address" name="billing_address" 
                                   value="{{ old('billing_address') }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="billing_city" class="form-label fw-bold">City *</label>
                                <input type="text" class="form-control" id="billing_city" name="billing_city" 
                                       value="{{ old('billing_city') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="billing_state" class="form-label fw-bold">State *</label>
                                <input type="text" class="form-control" id="billing_state" name="billing_state" 
                                       value="{{ old('billing_state') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="billing_zip" class="form-label fw-bold">ZIP Code *</label>
                                <input type="text" class="form-control" id="billing_zip" name="billing_zip" 
                                       value="{{ old('billing_zip') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_country" class="form-label fw-bold">Country *</label>
                            <select class="form-select" id="billing_country" name="billing_country" required>
                                <option value="">Select Country</option>
                                <option value="US" {{ old('billing_country') == 'US' ? 'selected' : '' }}>United States</option>
                                <option value="CA" {{ old('billing_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                <option value="UK" {{ old('billing_country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="AU" {{ old('billing_country') == 'AU' ? 'selected' : '' }}>Australia</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="modern-card animate-fade-in">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-receipt me-2"></i>
                            Order Summary
                        </h5>
                        
                        <div class="order-items mb-4">
                            @foreach($cart->items as $item)
                                <div class="order-item d-flex justify-content-between align-items-center mb-2">
                                    <div class="item-info">
                                        <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                    <div class="item-price fw-bold">
                                        ${{ number_format($item->total, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="order-summary">
                            <div class="summary-row d-flex justify-content-between mb-2">
                                <span>Subtotal ({{ $cart->item_count }} items)</span>
                                <span class="fw-bold">${{ number_format($cart->total, 2) }}</span>
                            </div>
                            <div class="summary-row d-flex justify-content-between mb-2">
                                <span>Tax (8%)</span>
                                <span class="fw-bold">${{ number_format($cart->total * 0.08, 2) }}</span>
                            </div>
                            <div class="summary-row d-flex justify-content-between mb-3">
                                <span>Shipping</span>
                                <span class="fw-bold">{{ $cart->total > 100 ? 'FREE' : '$10.00' }}</span>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="summary-row d-flex justify-content-between total">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-5 text-success">${{ number_format($cart->total + ($cart->total * 0.08) + ($cart->total > 100 ? 0 : 10), 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="checkout-actions mt-4">
                            <button type="submit" class="btn btn-checkout w-100" id="place-order-btn">
                                <i class="fas fa-lock me-2"></i>
                                Place Order Securely
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-gradient h4,
.card-gradient p {
    color: white !important;
}

.btn-checkout {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 12px;
    padding: 16px 24px;
    color: white;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-checkout:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    color: white;
}

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