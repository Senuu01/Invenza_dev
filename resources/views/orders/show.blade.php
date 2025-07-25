@extends('layouts.app')

@section('header', 'Order Details')

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
                                <i class="fas fa-receipt text-primary me-2"></i>
                                Order #{{ $order->order_number }}
                            </h4>
                            <p class="text-muted mb-0">Order details and tracking information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Orders
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

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8 mb-4">
            <!-- Order Status -->
            <div class="modern-card animate-fade-in mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Order Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Order Number</label>
                            <p class="form-control-plaintext fw-bold">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Order Date</label>
                            <p class="form-control-plaintext">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Order Status</label>
                            <p class="form-control-plaintext">{!! $order->status_badge !!}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Payment Status</label>
                            <p class="form-control-plaintext">{!! $order->payment_status_badge !!}</p>
                        </div>
                    </div>
                    
                    @if($order->payment_method)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Payment Method</label>
                                <p class="form-control-plaintext">
                                    @switch($order->payment_method)
                                        @case('credit_card')
                                            <i class="fas fa-credit-card me-2"></i>Credit Card
                                            @break
                                        @case('paypal')
                                            <i class="fab fa-paypal me-2"></i>PayPal
                                            @break
                                        @case('bank_transfer')
                                            <i class="fas fa-university me-2"></i>Bank Transfer
                                            @break
                                        @default
                                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                    @endswitch
                                </p>
                            </div>
                            @if($order->tracking_number)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Tracking Number</label>
                                    <p class="form-control-plaintext">{{ $order->tracking_number }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="modern-card animate-fade-in mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Order Items
                    </h5>
                    
                    <div class="order-items">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2 col-4">
                                        <div class="order-item-image">
                                            <div class="product-image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-8">
                                        <div class="order-item-details">
                                            <h6 class="fw-bold mb-1">{{ $item->product_name }}</h6>
                                            <p class="text-muted small mb-1">SKU: {{ $item->product_sku }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <div class="order-item-quantity">
                                            <span class="fw-bold">Qty: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <div class="order-item-price">
                                            <span class="fw-bold text-success">${{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <div class="order-item-total">
                                            <span class="fw-bold">${{ number_format($item->total, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Billing & Shipping Information -->
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Billing & Shipping Information
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Billing Address</h6>
                            <div class="address-info">
                                <p class="mb-1 fw-bold">{{ $order->billing_full_name }}</p>
                                <p class="mb-1">{{ $order->billing_address }}</p>
                                <p class="mb-1">{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
                                <p class="mb-1">{{ $order->billing_country }}</p>
                                <p class="mb-0 text-muted">{{ $order->billing_email }}</p>
                                @if($order->billing_phone)
                                    <p class="mb-0 text-muted">{{ $order->billing_phone }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Shipping Address</h6>
                            <div class="address-info">
                                <p class="mb-1 fw-bold">{{ $order->shipping_full_name }}</p>
                                <p class="mb-1">{{ $order->shipping_address }}</p>
                                <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                                <p class="mb-1">{{ $order->shipping_country }}</p>
                                <p class="mb-0 text-muted">{{ $order->shipping_email }}</p>
                                @if($order->shipping_phone)
                                    <p class="mb-0 text-muted">{{ $order->shipping_phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-calculator me-2"></i>
                        Order Summary
                    </h5>
                    
                    <div class="order-summary">
                        <div class="summary-row d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span class="fw-bold">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span class="fw-bold">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="summary-row d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span class="fw-bold">${{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        
                        @if($order->discount > 0)
                            <div class="summary-row d-flex justify-content-between mb-3">
                                <span>Discount</span>
                                <span class="fw-bold text-success">-${{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        
                        <hr class="my-3">
                        
                        <div class="summary-row d-flex justify-content-between total">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-success">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                    
                    @if($order->notes)
                        <div class="order-notes mt-4">
                            <h6 class="fw-bold mb-2">Order Notes</h6>
                            <p class="text-muted mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                    
                    <div class="order-actions mt-4">
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('orders.track', $order) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-truck me-2"></i>
                            Track Order
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

/* Order Item Styling */
.order-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
}

.order-item:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.order-item-image {
    text-align: center;
}

.product-image-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    background: rgba(59, 130, 246, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 20px;
    margin: 0 auto;
}

.order-item-details h6 {
    color: #1e293b;
    margin-bottom: 4px;
}

/* Address Info */
.address-info {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

/* Order Summary */
.summary-row {
    font-size: 14px;
}

.summary-row.total {
    font-size: 18px;
    border-top: 2px solid #e2e8f0;
    padding-top: 12px;
    margin-top: 8px;
}

/* Badge Styling */
.badge {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 8px;
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
    .order-item {
        padding: 16px;
    }
    
    .order-item-details h6 {
        font-size: 14px;
    }
    
    .address-info {
        margin-bottom: 16px;
    }
}
</style>
@endsection 