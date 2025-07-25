@extends('layouts.app')

@section('header', 'Product Catalog')

@section('content')
<div class="container-fluid px-2">
    <!-- Modern Hero Header -->
    <div class="hero-header mb-4">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="gradient-text">Discover</span> Amazing Products
                </h1>
                <p class="hero-subtitle">Explore our curated collection of premium products at unbeatable prices</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $products->count() }}+</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $categories->count() ?? 0 }}+</span>
                        <span class="stat-label">Categories</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-elements">
                    <div class="floating-icon" style="--delay: 0s">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="floating-icon" style="--delay: 1s">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="floating-icon" style="--delay: 2s">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="floating-icon" style="--delay: 3s">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Search and Filters -->
    <div class="search-filters-section mb-4">
        <div class="search-filters-container">
            <div class="search-section">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" 
                           placeholder="What are you looking for today?" onkeyup="filterProducts()">
                    <button class="search-btn" onclick="filterProducts()">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="filters-section">
                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select class="filter-select" id="categoryFilter" onchange="filterProducts()">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select class="filter-select" id="sortFilter" onchange="filterProducts()">
                        <option value="name">Name</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <button class="clear-filters-btn" onclick="clearFilters()">
                        <i class="fas fa-times me-2"></i>
                        Clear All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="productsGrid">
        @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-card" 
                 data-name="{{ strtolower($product->name) }}" 
                 data-category="{{ strtolower($product->category ? $product->category->name : 'uncategorized') }}"
                 data-price="{{ $product->price }}">
                <div class="product-card-modern h-100 animate-fade-in">
                    <!-- Product Image -->
                    <div class="product-image-container">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="product-image-placeholder" style="display: none;">
                                <i class="fas fa-image"></i>
                            </div>
                        @else
                            <div class="product-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        @if($product->quantity <= 10 && $product->quantity > 0)
                            <div class="stock-badge low-stock">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Low Stock
                            </div>
                        @elseif($product->quantity == 0)
                            <div class="stock-badge out-of-stock">
                                <i class="fas fa-times-circle me-1"></i>
                                Out of Stock
                            </div>
                        @else
                            <div class="stock-badge in-stock">
                                <i class="fas fa-check-circle me-1"></i>
                                In Stock
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="product-info">
                        <div class="product-header">
                            <h5 class="product-title">{{ $product->name }}</h5>
                            <div class="product-category">
                                <span class="category-badge">
                                    {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                </span>
                            </div>
                        </div>

                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>

                        <div class="product-details">
                            <div class="product-price">
                                <span class="price-currency">$</span>
                                <span class="price-amount">{{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="product-stock">
                                <span class="stock-text">{{ $product->quantity }} available</span>
                            </div>
                        </div>

                        <div class="product-actions">
                            <div class="d-flex gap-2">
                                <a href="{{ route('customer.products.show', $product) }}" class="btn btn-view-details flex-grow-1">
                                    <i class="fas fa-eye me-2"></i>
                                    View Details
                                </a>
                                @if($product->quantity > 0)
                                <button class="btn btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <div class="empty-state-icon mb-4">
                                <div class="icon-circle">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold text-dark mb-3">No Products Available</h4>
                            <p class="text-muted mb-4 fs-5">Check back later for amazing products!</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Modern Online Store Styling */
.card-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-gradient h4,
.card-gradient p {
    color: white !important;
}

/* Search Box Styling */
.search-box .input-group {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.search-box .input-group-text {
    border: 2px solid #e2e8f0;
    border-right: none;
    background: white;
}

.search-box .form-control {
    border: 2px solid #e2e8f0;
    border-left: none;
    padding: 12px 16px;
    font-size: 14px;
}

.search-box .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

/* Product Card Styling */
.product-card-modern {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.product-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

/* Product Image Container */
.product-image-container {
    position: relative;
    height: 200px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card-modern:hover .product-image {
    transform: scale(1.05);
}

.product-image-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 32px;
}

/* Stock Badge */
.stock-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stock-badge.in-stock {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.stock-badge.low-stock {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.stock-badge.out-of-stock {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* Product Info */
.product-info {
    padding: 20px;
}

.product-header {
    margin-bottom: 12px;
}

.product-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-category {
    margin-bottom: 12px;
}

.category-badge {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-description {
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.product-price {
    display: flex;
    align-items: baseline;
}

.price-currency {
    font-size: 14px;
    font-weight: 600;
    color: #059669;
    margin-right: 2px;
}

.price-amount {
    font-size: 20px;
    font-weight: 700;
    color: #059669;
}

.product-stock {
    text-align: right;
}

.stock-text {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

/* Product Actions */
.product-actions {
    text-align: center;
}

.btn-view-details {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none;
    border-radius: 12px;
    padding: 12px 20px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    width: 100%;
    text-decoration: none;
    display: inline-block;
}

.btn-view-details:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    color: white;
    text-decoration: none;
}

.btn-view-details:active {
    transform: translateY(0);
}

/* Add to Cart Button */
.btn-add-to-cart {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 12px;
    padding: 12px 16px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-add-to-cart:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    color: white;
}

.btn-add-to-cart:active {
    transform: translateY(0);
}

.btn-add-to-cart:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
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

/* Animation Classes */
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
    .product-card-modern {
        margin-bottom: 20px;
    }
    
    .product-image-container {
        height: 160px;
    }
    
    .product-info {
        padding: 16px;
    }
    
    .product-title {
        font-size: 14px;
    }
    
    .price-amount {
        font-size: 18px;
    }
}

/* Filter Animation */
.product-card {
    transition: all 0.3s ease;
}

.product-card.hidden {
    display: none;
}

.product-card.filtered {
    animation: filterIn 0.4s ease-out;
}

@keyframes filterIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    padding: 16px 20px;
    z-index: 9999;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    border-left: 4px solid #10b981;
}

.notification.show {
    transform: translateX(0);
}

.notification-error {
    border-left-color: #ef4444;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.notification-content i {
    font-size: 18px;
    color: #10b981;
}

.notification-error .notification-content i {
    color: #ef4444;
}

.notification-content span {
    font-weight: 500;
    color: #1e293b;
}

@media (max-width: 768px) {
    .notification {
        right: 10px;
        left: 10px;
        transform: translateY(-100px);
    }
    
    .notification.show {
        transform: translateY(0);
    }
}
</style>

<script>
function refreshPage() {
    window.location.reload();
}

function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const sortFilter = document.getElementById('sortFilter').value;
    
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        const productName = card.dataset.name;
        const productCategory = card.dataset.category;
        
        const matchesSearch = productName.includes(searchTerm);
        const matchesCategory = categoryFilter === '' || productCategory === categoryFilter;
        
        if (matchesSearch && matchesCategory) {
            card.classList.remove('hidden');
            card.classList.add('filtered');
        } else {
            card.classList.add('hidden');
            card.classList.remove('filtered');
        }
    });
    
    // Sort products if needed
    if (sortFilter !== 'name') {
        sortProducts(sortFilter);
    }
}

function sortProducts(sortType) {
    const productsGrid = document.getElementById('productsGrid');
    const productCards = Array.from(document.querySelectorAll('.product-card:not(.hidden)'));
    
    productCards.sort((a, b) => {
        switch(sortType) {
            case 'price_low':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price_high':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'newest':
                // For now, just sort by name since we don't have creation date
                return a.dataset.name.localeCompare(b.dataset.name);
            default:
                return 0;
        }
    });
    
    // Reorder DOM elements
    productCards.forEach(card => {
        productsGrid.appendChild(card);
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('sortFilter').value = 'name';
    
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.classList.remove('hidden', 'filtered');
    });
}

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add some delay to the animation for better visual effect
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Add to Cart Function
function addToCart(productId) {
    const button = event.target.closest('.btn-add-to-cart');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('quantity', 1);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Send request
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            // Update cart count and side cart
            updateCartCount();
            if (typeof loadCartItems === 'function') {
                loadCartItems();
            }
            
            // Show notification
            showNotification('Product added to cart!', 'success');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.style.background = '';
            }, 2000);
        } else {
            throw new Error(data.message || 'Failed to add to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = '<i class="fas fa-times"></i>';
        button.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        
        showNotification('Failed to add to cart', 'error');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            button.style.background = '';
        }, 2000);
    });
}

// Show notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Update cart count
function updateCartCount() {
    fetch('/cart/count')
    .then(response => response.json())
    .then(data => {
        const cartBadge = document.querySelector('.cart-count-badge');
        if (cartBadge) {
            cartBadge.textContent = data.count;
            cartBadge.style.display = data.count > 0 ? 'block' : 'none';
        }
    });
}
</script>
@endsection