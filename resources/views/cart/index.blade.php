@extends('layouts.app')

@section('header', 'Shopping Cart')

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
                                <i class="fas fa-shopping-cart text-primary me-2"></i>
                                Shopping Cart
                            </h4>
                            <p class="text-muted mb-0">Review your items and proceed to checkout</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Continue Shopping
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

    @if($cart->items->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="modern-card animate-fade-in">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Cart Items ({{ $cart->items->count() }})
                        </h5>
                        
                        <div class="cart-items" id="cart-items-container">
                            @foreach($cart->items as $item)
                                <div class="cart-item" data-item-id="{{ $item->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 col-4">
                                            <div class="cart-item-image">
                                                <div class="product-image-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-8">
                                            <div class="cart-item-details">
                                                <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                                                <p class="text-muted small mb-1">SKU: {{ $item->product->sku }}</p>
                                                <span class="badge bg-light text-dark">{{ $item->product->category->name ?? 'Uncategorized' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <div class="cart-item-price">
                                                <span class="fw-bold text-success">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <div class="cart-item-quantity">
                                                <div class="quantity-controls">
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity({{ $item->id }}, -1)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control quantity-input" 
                                                           value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}"
                                                           onchange="updateQuantity({{ $item->id }}, 0, this.value)">
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity({{ $item->id }}, 1)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-6">
                                            <div class="cart-item-total">
                                                <span class="fw-bold">${{ number_format($item->total, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-6">
                                            <div class="cart-item-actions">
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeItem({{ $item->id }})" title="Remove">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="cart-actions mt-4">
                            <button class="btn btn-outline-danger" onclick="clearCart()">
                                <i class="fas fa-trash me-2"></i>
                                Clear Cart
                            </button>
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
                        
                        <div class="order-summary" id="order-summary-container">
                            <div class="summary-row">
                                <span>Subtotal <span id="cart-item-count">({{ $cart->item_count }} items)</span></span>
                                <span class="fw-bold" id="cart-subtotal">${{ number_format($cart->total, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax (8%)</span>
                                <span class="fw-bold" id="cart-tax">${{ number_format($cart->total * 0.08, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span class="fw-bold" id="cart-shipping">{{ $cart->total > 100 ? 'FREE' : '$10.00' }}</span>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="summary-row total">
                                <span class="fw-bold">Total</span>
                                <span class="fw-bold fs-5 text-success" id="cart-total">${{ number_format($cart->total + ($cart->total * 0.08) + ($cart->total > 100 ? 0 : 10), 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="checkout-actions mt-4">
                            <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100">
                                <i class="fas fa-credit-card me-2"></i>
                                Proceed to Checkout
                            </a>
                        </div>
                        
                        <div class="payment-methods mt-3">
                            <p class="text-muted small mb-2">We accept:</p>
                            <div class="d-flex gap-2">
                                <i class="fab fa-cc-visa text-primary"></i>
                                <i class="fab fa-cc-mastercard text-primary"></i>
                                <i class="fab fa-cc-amex text-primary"></i>
                                <i class="fab fa-paypal text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="row">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body text-center py-5">
                        <div class="empty-cart" id="empty-cart-container">
                            <div class="empty-cart-icon mb-4">
                                <div class="icon-circle">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-dark mb-3">Your Cart is Empty</h4>
                            <p class="text-muted mb-4 fs-5">Looks like you haven't added any items to your cart yet.</p>
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
/* Modern Cart Styling */
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-gradient h4,
.card-gradient p {
    color: white !important;
}

/* Container and Layout Fixes */
.container-fluid {
    padding: 0 20px;
}

.modern-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.modern-card .card-body {
    padding: 24px;
}

/* Cart Item Styling */
.cart-item {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.cart-item:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transform: translateY(-3px);
    border-color: #3b82f6;
}

.cart-item .row {
    align-items: center;
    margin: 0;
}

.cart-item .col-md-2,
.cart-item .col-md-4,
.cart-item .col-md-1 {
    padding: 0 12px;
}

.cart-item-image {
    text-align: center;
    margin-bottom: 0;
}

.product-image-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 24px;
    margin: 0 auto;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.cart-item-details {
    padding: 0;
}

.cart-item-details h6 {
    color: #1e293b;
    margin-bottom: 8px;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.3;
}

.cart-item-details p {
    margin-bottom: 8px;
    font-size: 13px;
    line-height: 1.2;
}

.cart-item-details .badge {
    font-size: 11px;
    padding: 4px 8px;
}

.cart-item-price {
    text-align: center;
    padding: 0;
}

.cart-item-price span {
    font-size: 18px;
    font-weight: 700;
    display: block;
    line-height: 1.2;
}

.cart-item-quantity {
    text-align: center;
    padding: 0;
}

.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #f8fafc;
    border-radius: 12px;
    padding: 6px;
    border: 2px solid #e2e8f0;
    max-width: 140px;
    margin: 0 auto;
}

.quantity-controls .btn {
    border-radius: 6px;
    padding: 6px 8px;
    border: none;
    background: white;
    color: #64748b;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-controls .btn:hover {
    background: #3b82f6;
    color: white;
    transform: scale(1.05);
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: none;
    border-radius: 6px;
    padding: 6px;
    font-size: 13px;
    font-weight: 600;
    background: white;
    color: #1e293b;
    height: 32px;
}

.quantity-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.cart-item-total {
    text-align: center;
    padding: 0;
}

.cart-item-total span {
    font-size: 18px;
    font-weight: 700;
    color: #10b981;
    display: block;
    line-height: 1.2;
}

.cart-item-actions {
    text-align: center;
    padding: 0;
}

.cart-item-actions .btn {
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-item-actions .btn:hover {
    transform: scale(1.05);
}

/* Order Summary Styling */
.order-summary {
    background: #f8fafc;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    border: 2px solid #e2e8f0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    font-size: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total {
    font-size: 18px;
    font-weight: 700;
    border-top: 2px solid #3b82f6;
    padding-top: 16px;
    margin-top: 16px;
    color: #1e293b;
}

/* Checkout Button */
.btn-checkout {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 16px;
    padding: 18px 32px;
    color: white;
    font-weight: 700;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    width: 100%;
}

.btn-checkout:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    color: white;
}

/* Alert Styling */
.alert {
    border: none;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

/* Empty Cart */
.empty-cart {
    padding: 40px 20px;
    max-width: 400px;
    margin: 0 auto;
}

.empty-cart-icon {
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

/* Payment Methods */
.payment-methods i {
    font-size: 24px;
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
    .container-fluid {
        padding: 0 15px;
    }
    
    .modern-card .card-body {
        padding: 20px;
    }
    
    .cart-item {
        padding: 20px;
        margin-bottom: 16px;
    }
    
    .cart-item .col-md-2,
    .cart-item .col-md-4,
    .cart-item .col-md-1 {
        padding: 0 8px;
    }
    
    .cart-item-details h6 {
        font-size: 15px;
        margin-bottom: 6px;
    }
    
    .cart-item-details p {
        font-size: 12px;
        margin-bottom: 6px;
    }
    
    .product-image-placeholder {
        width: 60px;
        height: 60px;
        font-size: 18px;
    }
    
    .quantity-controls {
        flex-direction: row;
        gap: 4px;
        padding: 4px;
        max-width: 120px;
    }
    
    .quantity-controls .btn {
        min-width: 28px;
        height: 28px;
        padding: 4px 6px;
        font-size: 11px;
    }
    
    .quantity-input {
        width: 40px;
        padding: 4px;
        font-size: 12px;
        height: 28px;
    }
    
    .cart-item-price span,
    .cart-item-total span {
        font-size: 16px;
    }
    
    .cart-item-actions .btn {
        min-width: 32px;
        height: 32px;
        padding: 4px 8px;
        font-size: 11px;
    }
    
    .order-summary {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .summary-row {
        padding: 10px 0;
        font-size: 14px;
    }
    
    .summary-row.total {
        font-size: 16px;
        padding-top: 12px;
        margin-top: 12px;
    }
    
    .btn-checkout {
        padding: 16px 24px;
        font-size: 15px;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0 10px;
    }
    
    .cart-item {
        padding: 16px;
    }
    
    .cart-item .col-md-2,
    .cart-item .col-md-4,
    .cart-item .col-md-1 {
        padding: 0 6px;
    }
    
    .cart-item-details h6 {
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .cart-item-details p {
        font-size: 11px;
        margin-bottom: 4px;
    }
    
    .product-image-placeholder {
        width: 50px;
        height: 50px;
        font-size: 16px;
    }
    
    .quantity-controls {
        flex-direction: row;
        gap: 3px;
        padding: 3px;
        max-width: 100px;
    }
    
    .quantity-controls .btn {
        min-width: 24px;
        height: 24px;
        padding: 3px 4px;
        font-size: 10px;
    }
    
    .quantity-input {
        width: 35px;
        padding: 3px;
        font-size: 11px;
        height: 24px;
    }
    
    .cart-item-price span,
    .cart-item-total span {
        font-size: 14px;
    }
    
    .cart-item-actions .btn {
        min-width: 28px;
        height: 28px;
        padding: 3px 6px;
        font-size: 10px;
    }
}
</style>

<script>
// Cart Storage Management
const CART_STORAGE_KEY = 'invenza_cart';

// Initialize cart from localStorage or server
function initializeCart() {
    const storedCart = localStorage.getItem(CART_STORAGE_KEY);
    if (storedCart) {
        const cartData = JSON.parse(storedCart);
        updateCartDisplay(cartData);
    } else {
        loadCartFromServer();
    }
}

// Load cart from server
function loadCartFromServer() {
    fetch('/cart/items')
        .then(response => response.json())
        .then(data => {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(data));
            updateCartDisplay(data);
        })
        .catch(error => {
            console.error('Error loading cart:', error);
        });
}

// Update cart display
function updateCartDisplay(cartData) {
    if (!cartData.items || cartData.items.length === 0) {
        showEmptyCart();
        return;
    }

    // Update cart items
    renderCartItems(cartData.items);
    
    // Update order summary
    updateOrderSummary(cartData);
    
    // Update cart count in header
    updateCartCount(cartData.item_count);
    
    // Show cart content
    const cartItemsContainer = document.querySelector('.cart-items');
    const orderSummaryContainer = document.querySelector('.order-summary-container');
    const emptyCartContainer = document.querySelector('.empty-cart-container');
    
    if (cartItemsContainer) cartItemsContainer.style.display = 'block';
    if (orderSummaryContainer) orderSummaryContainer.style.display = 'block';
    if (emptyCartContainer) emptyCartContainer.style.display = 'none';
}

// Render cart items
function renderCartItems(items) {
    const cartItemsContainer = document.querySelector('.cart-items');
    
    const html = items.map(item => `
        <div class="cart-item" data-item-id="${item.id}">
            <div class="row align-items-center">
                <div class="col-md-2 col-4">
                    <div class="cart-item-image">
                        <div class="product-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-8">
                    <div class="cart-item-details">
                        <h6 class="fw-bold mb-1">${item.product.name}</h6>
                        <p class="text-muted small mb-1">SKU: ${item.product.sku}</p>
                        <span class="badge bg-light text-dark">${item.product.category ? item.product.category.name : 'Uncategorized'}</span>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="cart-item-price">
                        <span class="fw-bold text-success">$${parseFloat(item.price).toFixed(2)}</span>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="cart-item-quantity">
                        <div class="quantity-controls">
                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="form-control quantity-input" 
                                   value="${item.quantity}" min="1" max="${item.product.quantity}"
                                   onchange="updateQuantity(${item.id}, 0, this.value)">
                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 col-6">
                    <div class="cart-item-total">
                        <span class="fw-bold">$${parseFloat(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                </div>
                <div class="col-md-1 col-6">
                    <div class="cart-item-actions">
                        <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${item.id})" title="Remove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    cartItemsContainer.innerHTML = html;
}

// Update order summary
function updateOrderSummary(cartData) {
    const subtotal = parseFloat(cartData.total);
    const tax = subtotal * 0.08;
    const shipping = subtotal > 100 ? 0 : 10;
    const total = subtotal + tax + shipping;
    
    document.getElementById('cart-subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('cart-tax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('cart-shipping').textContent = subtotal > 100 ? 'FREE' : '$10.00';
    document.getElementById('cart-total').textContent = `$${total.toFixed(2)}`;
    document.getElementById('cart-item-count').textContent = `(${cartData.item_count} items)`;
}

// Update cart count in header
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        if (count > 0) {
            cartCountElement.textContent = count;
            cartCountElement.style.display = 'block';
        } else {
            cartCountElement.style.display = 'none';
        }
    }
}

// Show empty cart
function showEmptyCart() {
    document.querySelector('.cart-items').style.display = 'none';
    document.querySelector('.order-summary-container').style.display = 'none';
    document.querySelector('.empty-cart-container').style.display = 'block';
    updateCartCount(0);
}

// Update quantity function
function updateQuantity(itemId, change, newValue = null) {
    const quantityInput = event.target.closest('.quantity-controls').querySelector('.quantity-input');
    let quantity = parseInt(quantityInput.value);
    
    if (newValue !== null) {
        quantity = parseInt(newValue);
    } else {
        quantity += change;
    }
    
    if (quantity < 1) quantity = 1;
    
    // Update input value immediately for better UX
    quantityInput.value = quantity;
    
    // Show loading state
    const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
    cartItem.style.opacity = '0.7';
    
    // Send AJAX request
    fetch(`/cart/update/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Get fresh cart data from server
            return fetch('/cart/items');
        } else {
            // Revert input value on error
            quantityInput.value = quantity - change;
            throw new Error('Failed to update quantity');
        }
    })
    .then(response => response.json())
    .then(cartData => {
        // Update localStorage with fresh data
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartData));
        
        // Update display with fresh data
        updateCartDisplay(cartData);
        
        // Show success message
        showNotification('Quantity updated successfully!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert input value on error
        quantityInput.value = quantity - change;
        showNotification('Failed to update quantity', 'error');
    })
    .finally(() => {
        // Remove loading state
        cartItem.style.opacity = '1';
    });
}

// Remove item function
function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    // Show loading state
    const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
    cartItem.style.opacity = '0.7';
    
    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Get fresh cart data from server
            return fetch('/cart/items');
        } else {
            throw new Error('Failed to remove item');
        }
    })
    .then(response => response.json())
    .then(cartData => {
        // Update localStorage with fresh data
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartData));
        
        // Update display with fresh data
        updateCartDisplay(cartData);
        
        // Show success message
        showNotification('Item removed from cart!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to remove item', 'error');
    })
    .finally(() => {
        // Remove loading state
        cartItem.style.opacity = '1';
    });
}

// Clear cart function
function clearCart() {
    if (!confirm('Are you sure you want to clear your entire cart?')) {
        return;
    }
    
    fetch('/cart/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear localStorage
            localStorage.removeItem(CART_STORAGE_KEY);
            
            // Show empty cart
            showEmptyCart();
            
            // Show success message
            showNotification('Cart cleared successfully!', 'success');
        } else {
            showNotification('Failed to clear cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to clear cart', 'error');
    });
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Sync cart with server on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCart();
    
    // Sync with server every 5 minutes
    setInterval(loadCartFromServer, 300000);
});

// Handle page visibility change to sync cart
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        loadCartFromServer();
    }
});
</script>
@endsection 