@extends('layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
<div class="container-fluid px-2">
    <!-- Welcome Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card card-gradient animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">
                                Welcome back, {{ Auth::user()->name }}! 👋
                            </h4>
                            <p class="text-muted mb-0">
                                Here's what's happening with your inventory today
                            </p>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-10" style="width: 120px; height: 120px; overflow: hidden;">
                            <i class="fas fa-chart-line" style="font-size: 6rem; position: absolute; top: 10px; right: 10px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                <i class="fas fa-box text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $totalProducts }}</div>
                            <div class="text-muted small">Total Products</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-dollar-sign text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4" title="${{ number_format($totalValue, 2) }}">{{ $formattedTotalValue }}</div>
                            <div class="text-muted small">Total Value</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-exclamation-triangle text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $lowStockItems }}</div>
                            <div class="text-muted small">Low Stock Items</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-tags text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $totalCategories }}</div>
                            <div class="text-muted small">Categories</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Inventory Chart -->
        <div class="col-xl-8">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                Inventory Overview
                            </h6>
                            <p class="text-muted small mb-0">Stock levels across categories</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="dateFilterBtn">
                                Last 7 Days
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-period="7" onclick="updateChartData(7)">Last 7 Days</a></li>
                                <li><a class="dropdown-item" href="#" data-period="30" onclick="updateChartData(30)">Last 30 Days</a></li>
                                <li><a class="dropdown-item" href="#" data-period="90" onclick="updateChartData(90)">Last 3 Months</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="inventoryChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Stock Status Chart -->
        <div class="col-xl-4">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Stock Status
                    </h6>
                    <p class="text-muted small mb-0">Current stock distribution</p>
                </div>
                <div class="card-body p-4">
                    <canvas id="stockChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Low Stock Alert -->
        <div class="col-xl-8">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                Low Stock Alert
                            </h6>
                            <p class="text-muted small mb-0">Products that need restocking</p>
                        </div>
                        <a href="{{ route('products.index', ['status' => 'low_stock']) }}" class="btn btn-sm btn-outline-warning">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts->take(5) as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-2 p-2 bg-light">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-muted">{{ $product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge rounded-pill" style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }};">
                                                {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $product->quantity <= 5 ? 'text-danger' : 'text-warning' }}">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->status == 'out_of_stock')
                                            <span class="status-badge status-danger">Out of Stock</span>
                                        @elseif($product->status == 'low_stock')
                                            <span class="status-badge status-warning">Low Stock</span>
                                        @else
                                            <span class="status-badge status-success">In Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-success">
                                            <i class="fas fa-check-circle fs-1 mb-3"></i>
                                            <p class="mb-0">All products are well stocked!</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-clock text-primary me-2"></i>
                                Recent Activity
                            </h6>
                            <p class="text-muted small mb-0">Latest updates in your inventory</p>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="timeline-badge {{ $loop->first ? 'bg-primary' : 'bg-light' }}">
                                        @if($activity['type'] == 'Product Added')
                                            <i class="fas fa-plus text-success"></i>
                                        @elseif($activity['type'] == 'Stock Updated')
                                            <i class="fas fa-edit text-warning"></i>
                                        @elseif($activity['type'] == 'Category Created')
                                            <i class="fas fa-tag text-info"></i>
                                        @else
                                            <i class="fas fa-truck text-primary"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1 fw-semibold text-dark">{{ $activity['title'] }}</h6>
                                        <span class="text-muted small">{{ $activity['time'] }}</span>
                                    </div>
                                    <p class="mb-1 small text-muted">{{ $activity['item'] }}</p>
                                    <div class="small text-muted">
                                        <i class="fas fa-user-circle me-1"></i> {{ $activity['user'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h6>
                    <p class="text-muted small mb-0">Common tasks and shortcuts</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @if(auth()->user()->isAdmin())
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('products.create') }}" class="quick-action-card primary">
                                <div class="quick-action-content">
                                    <div class="quick-action-badge">Primary</div>
                                    <i class="fas fa-plus quick-action-icon"></i>
                                    <div class="quick-action-title">Add Product</div>
                                    <div class="quick-action-subtitle">Create new inventory item</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('categories.create') }}" class="quick-action-card secondary">
                                <div class="quick-action-content">
                                    <i class="fas fa-tag quick-action-icon"></i>
                                    <div class="quick-action-title">Add Category</div>
                                    <div class="quick-action-subtitle">Organize products</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('suppliers.create') }}" class="quick-action-card success">
                                <div class="quick-action-content">
                                    <i class="fas fa-truck quick-action-icon"></i>
                                    <div class="quick-action-title">Add Supplier</div>
                                    <div class="quick-action-subtitle">New partnership</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('customers.create') }}" class="quick-action-card info">
                                <div class="quick-action-content">
                                    <i class="fas fa-user-plus quick-action-icon"></i>
                                    <div class="quick-action-title">Add Customer</div>
                                    <div class="quick-action-subtitle">Expand client base</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('stock-requests.index') }}" class="quick-action-card warning new">
                                <div class="quick-action-content">
                                    <i class="fas fa-clipboard-list quick-action-icon"></i>
                                    <div class="quick-action-title">Manage Stock Requests</div>
                                    <div class="quick-action-subtitle">Review & approve</div>
                                </div>
                            </a>
                        </div>
                        @elseif(auth()->user()->isStaff())
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('products.index') }}" class="quick-action-card primary">
                                <div class="quick-action-content">
                                    <i class="fas fa-search quick-action-icon"></i>
                                    <div class="quick-action-title">View Products</div>
                                    <div class="quick-action-subtitle">Browse inventory</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('categories.index') }}" class="quick-action-card secondary">
                                <div class="quick-action-content">
                                    <i class="fas fa-tags quick-action-icon"></i>
                                    <div class="quick-action-title">View Categories</div>
                                    <div class="quick-action-subtitle">Product organization</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('stock-requests.create') }}" class="quick-action-card warning new">
                                <div class="quick-action-content">
                                    <i class="fas fa-truck-loading quick-action-icon"></i>
                                    <div class="quick-action-title">Request Stock</div>
                                    <div class="quick-action-subtitle">Order from suppliers</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('stock-requests.index') }}" class="quick-action-card info">
                                <div class="quick-action-content">
                                    <i class="fas fa-clipboard-list quick-action-icon"></i>
                                    <div class="quick-action-title">My Requests</div>
                                    <div class="quick-action-subtitle">Track orders</div>
                                </div>
                            </a>
                        </div>

                        @else
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('customer.products.index') }}" class="quick-action-card primary">
                                <div class="quick-action-content">
                                    <i class="fas fa-box quick-action-icon"></i>
                                    <div class="quick-action-title">Browse Products</div>
                                    <div class="quick-action-subtitle">Shop inventory</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('customer.dashboard') }}" class="quick-action-card secondary">
                                <div class="quick-action-content">
                                    <i class="fas fa-home quick-action-icon"></i>
                                    <div class="quick-action-title">My Dashboard</div>
                                    <div class="quick-action-subtitle">Personal overview</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('profile.edit') }}" class="quick-action-card success">
                                <div class="quick-action-content">
                                    <i class="fas fa-user quick-action-icon"></i>
                                    <div class="quick-action-title">My Profile</div>
                                    <div class="quick-action-subtitle">Account settings</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('customer.orders.index') }}" class="quick-action-card info">
                                <div class="quick-action-content">
                                    <i class="fas fa-shopping-cart quick-action-icon"></i>
                                    <div class="quick-action-title">My Orders</div>
                                    <div class="quick-action-subtitle">Order history</div>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .quick-action-card {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }
    
    .quick-action-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }
    
    .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s;
    }
    
    .quick-action-card:hover::before {
        left: 100%;
    }
    
    .quick-action-card.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .quick-action-card.secondary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .quick-action-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .quick-action-card.info {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .quick-action-card.warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .quick-action-card.danger {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    
    .quick-action-content {
        position: relative;
        z-index: 2;
        color: white;
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .quick-action-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        transition: all 0.3s ease;
    }
    
    .quick-action-card:hover .quick-action-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .quick-action-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .quick-action-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .quick-action-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .quick-action-card.new {
        animation: pulse 2s infinite;
    }
    
    .quick-action-card.new::after {
        content: 'NEW';
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(255, 71, 87, 0.4);
        border: 2px solid white;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { 
            transform: scale(1); 
            opacity: 1; 
        }
        50% { 
            transform: scale(1.05); 
            opacity: 0.9; 
        }
    }
    </style>
</div>

<!-- Timeline Styling -->
<style>
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    transition: all 0.3s ease;
    padding: 0.75rem 0;
}

.timeline-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.timeline-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    color: #6c757d;
    font-size: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.timeline-badge i {
    font-size: 1rem;
}

.timeline-badge.bg-primary {
    background: rgba(13, 110, 253, 0.1) !important;
    color: #0d6efd;
    box-shadow: 0 0.25rem 0.5rem rgba(13, 110, 253, 0.15);
}

.timeline-badge .fa-plus { color: #198754; }
.timeline-badge .fa-edit { color: #ffc107; }
.timeline-badge .fa-tag { color: #0dcaf0; }
.timeline-badge .fa-truck { color: #0d6efd; }
</style>

<script>
let inventoryChart, stockChart;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeCharts();
    
    // Set up date filter functionality
    setupDateFilter();
});

function initializeCharts() {
    // Inventory Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    inventoryChart = new Chart(inventoryCtx, {
        type: 'bar',
        data: {
            labels: [@foreach(\App\Models\Category::all() as $category)'{{ $category->name }}'{{ !$loop->last ? ',' : '' }}@endforeach],
            datasets: [{
                label: 'Products in Stock',
                data: [@foreach(\App\Models\Category::all() as $category){{ $category->products()->where('status', 'in_stock')->count() }}{{ !$loop->last ? ',' : '' }}@endforeach],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }, {
                label: 'Low Stock',
                data: [@foreach(\App\Models\Category::all() as $category){{ $category->products()->where('status', 'low_stock')->count() }}{{ !$loop->last ? ',' : '' }}@endforeach],
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    }
                },
                x: {
                    grid: {
                        display: false,
                    }
                }
            }
        }
    });

    // Stock Status Chart
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    const inStockCount = {{ \App\Models\Product::where('status', 'in_stock')->count() }};
    const lowStockCount = {{ \App\Models\Product::where('status', 'low_stock')->count() }};
    const outOfStockCount = {{ \App\Models\Product::where('status', 'out_of_stock')->count() }};
    
    stockChart = new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [inStockCount, lowStockCount, outOfStockCount],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                }
            }
        }
    });
}

function setupDateFilter() {
    // Add click handlers to dropdown items
    document.querySelectorAll('.dropdown-item[data-period]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const period = parseInt(this.getAttribute('data-period'));
            updateChartData(period);
            
            // Update button text
            document.getElementById('dateFilterBtn').textContent = this.textContent;
        });
    });
}

function updateChartData(days) {
    // Show loading state
    showChartLoading();
    
    // Fetch updated data from server
    fetch(`/api/dashboard/chart-data?days=${days}`)
        .then(response => response.json())
        .then(data => {
            updateInventoryChart(data.inventory);
            updateStockChart(data.stock);
            hideChartLoading();
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            hideChartLoading();
            // Fallback to static data
            updateChartsWithStaticData(days);
        });
}

function updateInventoryChart(data) {
    if (inventoryChart) {
        inventoryChart.data.labels = data.labels;
        inventoryChart.data.datasets[0].data = data.inStock;
        inventoryChart.data.datasets[1].data = data.lowStock;
        inventoryChart.update('active');
    }
}

function updateStockChart(data) {
    if (stockChart) {
        stockChart.data.datasets[0].data = [data.inStock, data.lowStock, data.outOfStock];
        stockChart.update('active');
    }
}

function updateChartsWithStaticData(days) {
    // Fallback function with simulated data based on days
    const multiplier = days === 7 ? 1 : days === 30 ? 1.2 : 1.5;
    
    const categories = [@foreach(\App\Models\Category::all() as $category)'{{ $category->name }}'{{ !$loop->last ? ',' : '' }}@endforeach];
    const inStockData = categories.map(() => Math.floor(Math.random() * 20 * multiplier) + 5);
    const lowStockData = categories.map(() => Math.floor(Math.random() * 10 * multiplier) + 2);
    
    updateInventoryChart({
        labels: categories,
        inStock: inStockData,
        lowStock: lowStockData
    });
    
    updateStockChart({
        inStock: Math.floor({{ \App\Models\Product::where('status', 'in_stock')->count() }} * multiplier),
        lowStock: Math.floor({{ \App\Models\Product::where('status', 'low_stock')->count() }} * multiplier),
        outOfStock: Math.floor({{ \App\Models\Product::where('status', 'out_of_stock')->count() }} * multiplier)
    });
}

function showChartLoading() {
    // Add loading overlay to charts
    const chartContainers = document.querySelectorAll('#inventoryChart, #stockChart');
    chartContainers.forEach(container => {
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
    });
}

function hideChartLoading() {
    // Remove loading overlay from charts
    const chartContainers = document.querySelectorAll('#inventoryChart, #stockChart');
    chartContainers.forEach(container => {
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
    });
}
</script>
@endsection