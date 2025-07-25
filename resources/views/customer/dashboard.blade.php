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

    <!-- Enhanced Search and Filters -->
    <div class="enhanced-search-section mb-4">
        <div class="search-container">
            <!-- Main Search Bar -->
            <div class="main-search-wrapper">
                <div class="search-input-container">
                    <i class="fas fa-search search-icon-main"></i>
                    <input type="text" 
                           class="enhanced-search-input" 
                           id="searchInput" 
                           placeholder="Search products, categories, or descriptions..."
                           autocomplete="off"
                           onkeyup="debounceSearch()"
                           onfocus="showSearchSuggestions()"
                           onblur="hideSearchSuggestions()">
                    <div class="search-actions">
                        <button class="search-clear-btn" id="clearSearchBtn" onclick="clearSearch()" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="search-submit-btn" onclick="performSearch()">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Search Suggestions -->
                <div class="search-suggestions" id="searchSuggestions">
                    <div class="suggestions-header">
                        <span>Popular Searches</span>
                    </div>
                    <div class="suggestion-items">
                        <div class="suggestion-item" onclick="setSearchTerm('electronics')">
                            <i class="fas fa-laptop"></i>
                            <span>Electronics</span>
                        </div>
                        <div class="suggestion-item" onclick="setSearchTerm('clothing')">
                            <i class="fas fa-tshirt"></i>
                            <span>Clothing</span>
                        </div>
                        <div class="suggestion-item" onclick="setSearchTerm('accessories')">
                            <i class="fas fa-headphones"></i>
                            <span>Accessories</span>
                        </div>
                        <div class="suggestion-item" onclick="setSearchTerm('stripe test')">
                            <i class="fas fa-flask"></i>
                            <span>Test Products</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Advanced Filters -->
            <div class="advanced-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-tags"></i>
                            Category
                        </label>
                        <select class="enhanced-filter-select" id="categoryFilter" onchange="filterProducts()">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-sort"></i>
                            Sort By
                        </label>
                        <select class="enhanced-filter-select" id="sortFilter" onchange="filterProducts()">
                            <option value="name">Name A-Z</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="newest">Newest First</option>
                            <option value="stock">In Stock First</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-dollar-sign"></i>
                            Price Range
                        </label>
                        <select class="enhanced-filter-select" id="priceFilter" onchange="filterProducts()">
                            <option value="">All Prices</option>
                            <option value="0-10">$0 - $10</option>
                            <option value="10-50">$10 - $50</option>
                            <option value="50-100">$50 - $100</option>
                            <option value="100+">$100+</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-box"></i>
                            Availability
                        </label>
                        <select class="enhanced-filter-select" id="availabilityFilter" onchange="filterProducts()">
                            <option value="">All Items</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button class="clear-all-filters-btn" onclick="clearAllFilters()">
                        <i class="fas fa-undo"></i>
                        Clear All Filters
                    </button>
                    <div class="active-filters" id="activeFilters">
                        <!-- Active filters will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="productsGrid">
        @forelse($products as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-card" 
                 data-name="{{ strtolower($product->name) }}" 
                 data-description="{{ strtolower($product->description) }}"
                 data-category="{{ strtolower($product->category ? $product->category->name : 'uncategorized') }}"
                 data-price="{{ $product->price }}"
                 data-quantity="{{ $product->quantity }}"
                 data-stock-status="{{ $product->quantity > 10 ? 'in_stock' : ($product->quantity > 0 ? 'low_stock' : 'out_of_stock') }}"
                 data-sku="{{ strtolower($product->sku) }}">
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
/* Modern Hero Header */
.hero-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem 2rem;
    margin: 0 -0.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
}

.hero-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 2;
}

.hero-text {
    flex: 1;
    max-width: 600px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1rem;
    line-height: 1.1;
}

.gradient-text {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #ffd700;
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.25rem;
}

.hero-visual {
    flex: 0 0 300px;
    position: relative;
    height: 200px;
}

.floating-elements {
    position: relative;
    width: 100%;
    height: 100%;
}

.floating-icon {
    position: absolute;
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    animation: float 3s ease-in-out infinite;
    animation-delay: var(--delay);
}

.floating-icon:nth-child(1) { top: 20%; left: 20%; }
.floating-icon:nth-child(2) { top: 60%; right: 20%; }
.floating-icon:nth-child(3) { bottom: 20%; left: 40%; }
.floating-icon:nth-child(4) { top: 40%; right: 40%; }

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Remove default browser outlines */
.enhanced-search-section * {
    outline: none !important;
}

.enhanced-search-section *:focus {
    outline: none !important;
    box-shadow: none !important;
}

/* Enhanced Search and Filters */
.enhanced-search-section {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid #f1f5f9;
    margin-bottom: 2rem;
}

.search-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Main Search Bar */
.main-search-wrapper {
    position: relative;
}

.search-input-container {
    position: relative;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 0.75rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.search-input-container:focus-within {
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 8px 24px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    outline: none;
}

.search-input-container *:focus {
    outline: none;
    box-shadow: none;
}

.search-icon-main {
    color: #64748b;
    font-size: 1.2rem;
    margin-left: 1rem;
    margin-right: 1rem;
    transition: color 0.3s ease;
}

.search-input-container:focus-within .search-icon-main {
    color: #3b82f6;
}

.enhanced-search-input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 1.1rem;
    padding: 0.75rem 0;
    outline: none;
    color: #1e293b;
    font-weight: 500;
    box-shadow: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.enhanced-search-input:focus {
    outline: none;
    box-shadow: none;
    border: none;
}

.enhanced-search-input::-webkit-search-decoration,
.enhanced-search-input::-webkit-search-cancel-button,
.enhanced-search-input::-webkit-search-results-button,
.enhanced-search-input::-webkit-search-results-decoration {
    -webkit-appearance: none;
    appearance: none;
}

.enhanced-search-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.search-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-right: 0.5rem;
}

.search-clear-btn {
    background: #e2e8f0;
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-clear-btn:hover {
    background: #cbd5e1;
    color: #475569;
    transform: scale(1.05);
}

.search-submit-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border: none;
    border-radius: 10px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.search-submit-btn:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    border: 1px solid #e2e8f0;
    z-index: 1000;
    margin-top: 0.5rem;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.search-suggestions.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.suggestions-header {
    padding: 1rem 1.5rem 0.5rem;
    border-bottom: 1px solid #f1f5f9;
}

.suggestions-header span {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.suggestion-items {
    padding: 0.5rem 0;
}

.suggestion-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #374151;
}

.suggestion-item:hover {
    background: #f8fafc;
    color: #3b82f6;
}

.suggestion-item i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.suggestion-item span {
    font-weight: 500;
}

/* Advanced Filters */
.advanced-filters {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
}

.filter-label i {
    color: #64748b;
    font-size: 0.875rem;
}

.enhanced-filter-select {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.875rem;
    background: white;
    color: #1e293b;
    transition: all 0.3s ease;
    cursor: pointer;
    font-weight: 500;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.enhanced-filter-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none !important;
}

.enhanced-filter-select:hover {
    border-color: #cbd5e1;
}

/* Filter Actions */
.filter-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.clear-all-filters-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.clear-all-filters-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.active-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.active-filter-tag {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    border: 1px solid #bfdbfe;
}

.active-filter-tag .remove-filter {
    cursor: pointer;
    font-weight: bold;
    margin-left: 0.25rem;
}

.active-filter-tag .remove-filter:hover {
    color: #dc2626;
}

/* Results Count */
.results-count {
    margin-top: 1rem;
    padding: 1rem;
    background: #f0f9ff;
    border-radius: 8px;
    border: 1px solid #bae6fd;
}

.results-count-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #0369a1;
    font-weight: 500;
}

.results-count-content i {
    color: #0ea5e9;
}

/* Search input clear button visibility */
.search-input-container:has(.enhanced-search-input:not(:placeholder-shown)) .search-clear-btn {
    display: flex !important;
}

/* Modern Pull-to-Refresh Component */
.pull-refresh-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    pointer-events: none;
    overflow: hidden;
}

.pull-refresh-indicator {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    text-align: center;
    transform: translateY(-100%);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.pull-refresh-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    font-weight: 600;
    font-size: 16px;
    max-width: 400px;
    margin: 0 auto;
}

.pull-refresh-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.pull-refresh-icon i {
    font-size: 18px;
    color: white;
    transition: all 0.3s ease;
}

.pull-refresh-text {
    font-weight: 600;
    letter-spacing: 0.5px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pull-refresh-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6);
    transition: width 0.3s ease;
    border-radius: 0 0 0 3px;
}

/* Loading States */
.pull-refresh-indicator.loading {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.pull-refresh-indicator.loading .pull-refresh-icon {
    background: rgba(255, 255, 255, 0.3);
    animation: pulse 1.5s infinite;
}

.pull-refresh-indicator.loading .pull-refresh-icon i {
    animation: spin 1s linear infinite;
}

/* Ready State */
.pull-refresh-indicator.ready {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.pull-refresh-indicator.ready .pull-refresh-icon {
    background: rgba(255, 255, 255, 0.3);
    animation: pulse 1s infinite;
}

.pull-refresh-indicator.ready .pull-refresh-icon i {
    animation: bounce 0.6s ease;
}

/* Success State */
.pull-refresh-indicator.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.pull-refresh-indicator.success .pull-refresh-icon {
    background: rgba(255, 255, 255, 0.3);
    animation: bounce 0.6s ease;
}

/* Animations */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
    40%, 43% { transform: translate3d(0, -8px, 0); }
    70% { transform: translate3d(0, -4px, 0); }
    90% { transform: translate3d(0, -2px, 0); }
}

/* Modern Loading Spinner */
.refresh-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(10px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.refresh-loading-overlay.show {
    opacity: 1;
}

.refresh-loading-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.8);
    transition: transform 0.3s ease;
    max-width: 300px;
    width: 90%;
}

.refresh-loading-overlay.show .refresh-loading-card {
    transform: scale(1);
}

.refresh-loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

.refresh-loading-text {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.refresh-loading-subtext {
    font-size: 14px;
    color: #6b7280;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        justify-content: center;
        gap: 1.5rem;
    }
    
    .enhanced-search-section {
        padding: 1.5rem;
        margin: 0 -0.5rem 2rem;
        border-radius: 16px;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .clear-all-filters-btn {
        width: 100%;
        justify-content: center;
    }
    
    .active-filters {
        justify-content: center;
    }
    
    .search-input-container {
        padding: 0.5rem;
    }
    
    .enhanced-search-input {
        font-size: 1rem;
    }
    
    .search-suggestions {
        position: fixed;
        top: auto;
        bottom: 0;
        left: 0;
        right: 0;
        border-radius: 12px 12px 0 0;
        margin-top: 0;
        max-height: 50vh;
        overflow-y: auto;
    }
}

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
// Pull-to-refresh functionality
let startY = 0;
let currentY = 0;
let pullDistance = 0;
let isPulling = false;
let refreshThreshold = 100;

function initPullToRefresh() {
    const body = document.body;
    
    body.addEventListener('touchstart', handleTouchStart, { passive: false });
    body.addEventListener('touchmove', handleTouchMove, { passive: false });
    body.addEventListener('touchend', handleTouchEnd, { passive: false });
    
    // Also support mouse events for desktop
    body.addEventListener('mousedown', handleMouseStart, { passive: false });
    body.addEventListener('mousemove', handleMouseMove, { passive: false });
    body.addEventListener('mouseup', handleMouseEnd, { passive: false });
}

function handleTouchStart(e) {
    if (window.scrollY === 0) {
        startY = e.touches[0].clientY;
        isPulling = true;
    }
}

function handleTouchMove(e) {
    if (!isPulling) return;
    
    currentY = e.touches[0].clientY;
    pullDistance = currentY - startY;
    
    if (pullDistance > 0 && window.scrollY === 0) {
        e.preventDefault();
        showPullIndicator(pullDistance);
    }
}

function handleTouchEnd(e) {
    if (isPulling && pullDistance > refreshThreshold) {
        performRefresh();
    }
    hidePullIndicator();
    isPulling = false;
    pullDistance = 0;
}

function handleMouseStart(e) {
    if (window.scrollY === 0) {
        startY = e.clientY;
        isPulling = true;
    }
}

function handleMouseMove(e) {
    if (!isPulling) return;
    
    currentY = e.clientY;
    pullDistance = currentY - startY;
    
    if (pullDistance > 0 && window.scrollY === 0) {
        showPullIndicator(pullDistance);
    }
}

function handleMouseEnd(e) {
    if (isPulling && pullDistance > refreshThreshold) {
        performRefresh();
    }
    hidePullIndicator();
    isPulling = false;
    pullDistance = 0;
}

function showPullIndicator(distance) {
    let container = document.getElementById('pull-refresh-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'pull-refresh-container';
        container.className = 'pull-refresh-container';
        document.body.appendChild(container);
    }
    
    let indicator = document.getElementById('pull-refresh-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'pull-refresh-indicator';
        indicator.className = 'pull-refresh-indicator';
        indicator.innerHTML = `
            <div class="pull-refresh-content">
                <div class="pull-refresh-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <span class="pull-refresh-text">Pull to refresh</span>
            </div>
            <div class="pull-refresh-progress"></div>
        `;
        container.appendChild(indicator);
    }
    
    const progress = Math.min(distance / refreshThreshold, 1);
    const translateY = Math.min(distance * 0.6, 80);
    
    indicator.style.transform = `translateY(${translateY}px)`;
    indicator.style.opacity = progress;
    
    const progressBar = indicator.querySelector('.pull-refresh-progress');
    progressBar.style.width = `${progress * 100}%`;
    
    const icon = indicator.querySelector('.pull-refresh-icon i');
    const text = indicator.querySelector('.pull-refresh-text');
    
    if (distance > refreshThreshold) {
        icon.className = 'fas fa-check';
        text.textContent = 'Release to refresh';
        indicator.classList.add('ready');
    } else {
        icon.className = 'fas fa-arrow-down';
        text.textContent = 'Pull to refresh';
        indicator.classList.remove('ready');
    }
}

function hidePullIndicator() {
    const container = document.getElementById('pull-refresh-container');
    const indicator = document.getElementById('pull-refresh-indicator');
    
    if (indicator) {
        indicator.style.opacity = '0';
        indicator.style.transform = 'translateY(-100%)';
        
        setTimeout(() => {
            if (container && container.parentNode) {
                container.parentNode.removeChild(container);
            }
        }, 400);
    }
}

function performRefresh() {
    // Show loading state with modern overlay
    const productsGrid = document.getElementById('productsGrid');
    productsGrid.style.opacity = '0.3';
    productsGrid.style.pointerEvents = 'none';
    
    // Create modern loading overlay
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'refresh-loading-overlay';
    loadingOverlay.innerHTML = `
        <div class="refresh-loading-card">
            <div class="refresh-loading-spinner"></div>
            <div class="refresh-loading-text">Refreshing Products</div>
            <div class="refresh-loading-subtext">Please wait while we update the catalog...</div>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
    
    // Show loading state
    setTimeout(() => {
        loadingOverlay.classList.add('show');
    }, 100);
    
    // Update pull indicator to loading state
    const indicator = document.getElementById('pull-refresh-indicator');
    if (indicator) {
        indicator.classList.add('loading');
        const icon = indicator.querySelector('.pull-refresh-icon i');
        const text = indicator.querySelector('.pull-refresh-text');
        icon.className = 'fas fa-spinner';
        text.textContent = 'Refreshing...';
    }
    
    // Refresh the page after a smooth delay
    setTimeout(() => {
        window.location.reload();
    }, 800);
}

// Legacy refresh function for compatibility
function refreshPage() {
    performRefresh();
}

// Debounce function for search
let searchTimeout;
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        filterProducts();
    }, 300);
}

// Enhanced search functionality
function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const sortFilter = document.getElementById('sortFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    const availabilityFilter = document.getElementById('availabilityFilter').value;
    
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
        const productName = card.dataset.name;
        const productDescription = card.dataset.description;
        const productCategory = card.dataset.category;
        const productPrice = parseFloat(card.dataset.price);
        const productStockStatus = card.dataset.stockStatus;
        const productSku = card.dataset.sku;
        
        // Search matching (name, description, SKU)
        const matchesSearch = searchTerm === '' || 
            productName.includes(searchTerm) || 
            productDescription.includes(searchTerm) || 
            productSku.includes(searchTerm);
        
        // Category matching
        const matchesCategory = categoryFilter === '' || productCategory === categoryFilter;
        
        // Price range matching
        let matchesPrice = true;
        if (priceFilter !== '') {
            switch(priceFilter) {
                case '0-10':
                    matchesPrice = productPrice >= 0 && productPrice <= 10;
                    break;
                case '10-50':
                    matchesPrice = productPrice > 10 && productPrice <= 50;
                    break;
                case '50-100':
                    matchesPrice = productPrice > 50 && productPrice <= 100;
                    break;
                case '100+':
                    matchesPrice = productPrice > 100;
                    break;
            }
        }
        
        // Availability matching
        const matchesAvailability = availabilityFilter === '' || productStockStatus === availabilityFilter;
        
        // Show/hide based on all filters
        if (matchesSearch && matchesCategory && matchesPrice && matchesAvailability) {
            card.classList.remove('hidden');
            card.classList.add('filtered');
            visibleCount++;
        } else {
            card.classList.add('hidden');
            card.classList.remove('filtered');
        }
    });
    
    // Update active filters display
    updateActiveFilters();
    
    // Show results count
    showResultsCount(visibleCount);
    
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
            case 'stock':
                // Sort by stock status: in_stock first, then low_stock, then out_of_stock
                const stockOrder = { 'in_stock': 0, 'low_stock': 1, 'out_of_stock': 2 };
                return stockOrder[a.dataset.stockStatus] - stockOrder[b.dataset.stockStatus];
            default:
                return a.dataset.name.localeCompare(b.dataset.name);
        }
    });
    
    // Reorder DOM elements
    productCards.forEach(card => {
        productsGrid.appendChild(card);
    });
}

// Search suggestion functions
function showSearchSuggestions() {
    const suggestions = document.getElementById('searchSuggestions');
    suggestions.classList.add('show');
}

function hideSearchSuggestions() {
    setTimeout(() => {
        const suggestions = document.getElementById('searchSuggestions');
        suggestions.classList.remove('show');
    }, 200);
}

function setSearchTerm(term) {
    document.getElementById('searchInput').value = term;
    filterProducts();
    hideSearchSuggestions();
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('clearSearchBtn').style.display = 'none';
    filterProducts();
}

function performSearch() {
    filterProducts();
    hideSearchSuggestions();
}

// Update active filters display
function updateActiveFilters() {
    const activeFiltersContainer = document.getElementById('activeFilters');
    const searchTerm = document.getElementById('searchInput').value;
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    const availabilityFilter = document.getElementById('availabilityFilter').value;
    
    let activeFiltersHTML = '';
    
    if (searchTerm) {
        activeFiltersHTML += `<span class="active-filter-tag">
            Search: "${searchTerm}" <span class="remove-filter" onclick="clearSearch()">×</span>
        </span>`;
    }
    
    if (categoryFilter) {
        activeFiltersHTML += `<span class="active-filter-tag">
            Category: ${categoryFilter} <span class="remove-filter" onclick="clearCategoryFilter()">×</span>
        </span>`;
    }
    
    if (priceFilter) {
        activeFiltersHTML += `<span class="active-filter-tag">
            Price: ${priceFilter} <span class="remove-filter" onclick="clearPriceFilter()">×</span>
        </span>`;
    }
    
    if (availabilityFilter) {
        activeFiltersHTML += `<span class="active-filter-tag">
            ${availabilityFilter.replace('_', ' ')} <span class="remove-filter" onclick="clearAvailabilityFilter()">×</span>
        </span>`;
    }
    
    activeFiltersContainer.innerHTML = activeFiltersHTML;
}

// Clear individual filters
function clearCategoryFilter() {
    document.getElementById('categoryFilter').value = '';
    filterProducts();
}

function clearPriceFilter() {
    document.getElementById('priceFilter').value = '';
    filterProducts();
}

function clearAvailabilityFilter() {
    document.getElementById('availabilityFilter').value = '';
    filterProducts();
}

// Show results count
function showResultsCount(count) {
    const totalProducts = document.querySelectorAll('.product-card').length;
    
    // Create or update results count element
    let resultsCount = document.getElementById('resultsCount');
    if (!resultsCount) {
        resultsCount = document.createElement('div');
        resultsCount.id = 'resultsCount';
        resultsCount.className = 'results-count';
        document.querySelector('.enhanced-search-section').appendChild(resultsCount);
    }
    
    if (count === totalProducts) {
        resultsCount.style.display = 'none';
    } else {
        resultsCount.style.display = 'block';
        resultsCount.innerHTML = `
            <div class="results-count-content">
                <i class="fas fa-search"></i>
                <span>Showing ${count} of ${totalProducts} products</span>
            </div>
        `;
    }
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('sortFilter').value = 'name';
    document.getElementById('priceFilter').value = '';
    document.getElementById('availabilityFilter').value = '';
    document.getElementById('clearSearchBtn').style.display = 'none';
    
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.classList.remove('hidden', 'filtered');
    });
    
    updateActiveFilters();
    showResultsCount(document.querySelectorAll('.product-card').length);
}

// Legacy function for compatibility
function clearFilters() {
    clearAllFilters();
}

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize pull-to-refresh functionality
    initPullToRefresh();
    
    // Initialize search functionality
    initializeSearch();
    
    // Add some delay to the animation for better visual effect
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Initialize search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    
    // Show/hide clear button based on input
    searchInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            clearSearchBtn.style.display = 'flex';
        } else {
            clearSearchBtn.style.display = 'none';
        }
    });
    
    // Handle Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        const suggestions = document.getElementById('searchSuggestions');
        const searchContainer = document.querySelector('.main-search-wrapper');
        
        if (!searchContainer.contains(e.target)) {
            suggestions.classList.remove('show');
        }
    });
}

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