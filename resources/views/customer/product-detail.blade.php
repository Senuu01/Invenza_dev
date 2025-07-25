@extends('layouts.app')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <span>Product Details</span>
    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Catalog
    </a>
</div>
@endsection

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $product->name }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 300px; display: none;">
                                    <i class="fas fa-box fs-1 text-muted"></i>
                                </div>
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 300px;">
                                    <i class="fas fa-box fs-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="fw-bold">{{ $product->name }}</h2>
                                @if($product->quantity > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success fs-6">
                                        In Stock
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger fs-6">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-muted mb-4">{{ $product->description }}</p>
                            
                            <div class="mb-3">
                                <span class="badge rounded-pill bg-light text-dark">
                                    {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong>Price:</strong>
                                        <div class="h3 text-success fw-bold">${{ number_format($product->price, 2) }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Available:</strong>
                                        <div class="h5 fw-bold">{{ $product->quantity }} units</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($product->sku)
                                <div class="mb-3">
                                    <strong>SKU:</strong> 
                                    <span class="font-monospace">{{ $product->sku }}</span>
                                </div>
                            @endif
                            
                            @if($product->supplier)
                                <div class="mb-3">
                                    <strong>Supplier:</strong> {{ $product->supplier->name }}
                                </div>
                            @endif
                            
                            @if($product->quantity > 0)
                                <div class="mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <label for="quantity" class="form-label fw-bold">Quantity:</label>
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="{{ $product->quantity }}">
                                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-success btn-lg flex-fill" onclick="addToCart()">
                                                    <i class="fas fa-shopping-cart me-2"></i>
                                                    Add to Cart
                                                </button>
                                                <a href="{{ route('cart.index') }}" class="btn btn-outline-success btn-lg">
                                                    <i class="fas fa-shopping-bag me-2"></i>
                                                    View Cart
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-4">
                                    <button class="btn btn-danger btn-lg w-100" disabled>
                                        <i class="fas fa-times me-2"></i>
                                        Out of Stock
                                    </button>
                                </div>
                            @endif
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-modern">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Back to Catalog
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let quantity = parseInt(quantityInput.value) + change;
    
    if (quantity < 1) quantity = 1;
    if (quantity > {{ $product->quantity }}) quantity = {{ $product->quantity }};
    
    quantityInput.value = quantity;
}

function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const addToCartBtn = document.querySelector('button[onclick="addToCart()"]');
    
    // Show loading state
    addToCartBtn.disabled = true;
    addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
    
    fetch(`/cart/add/{{ $product->id }}`, {
        method: 'POST',
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
            // Show success notification
            showNotification('Product added to cart successfully!', 'success');
            
            // Update cart count in header if it exists
            const cartCount = document.querySelector('.cart-count');
            if (cartCount && data.cart) {
                cartCount.textContent = data.cart.item_count;
                cartCount.style.display = 'block';
            }
            
            // Reset quantity to 1
            document.getElementById('quantity').value = 1;
        } else {
            showNotification(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to add product to cart', 'error');
    })
    .finally(() => {
        // Reset button state
        addToCartBtn.disabled = false;
        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
    });
}

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

// Handle quantity input validation
document.getElementById('quantity').addEventListener('change', function() {
    let value = parseInt(this.value);
    if (value < 1) this.value = 1;
    if (value > {{ $product->quantity }}) this.value = {{ $product->quantity }};
});
</script>
@endsection