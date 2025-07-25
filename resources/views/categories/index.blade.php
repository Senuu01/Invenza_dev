@extends('layouts.app')

@section('header', 'Categories Management')

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
                                <i class="fas fa-tags text-primary me-2"></i>
                                Categories Management
                            </h4>
                            <p class="text-muted mb-0">Organize your products with categories</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button onclick="refreshPage()" class="btn btn-outline-modern">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh
                            </button>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-plus me-2"></i>
                                Add Category
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

    <!-- Statistics Cards -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-tags text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ $categories->total() }}</div>
                            <div class="text-muted small">Total Categories</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                                <i class="fas fa-box text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\Product::count() }}</div>
                            <div class="text-muted small">Total Products</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-chart-bar text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ number_format(\App\Models\Product::count() / max($categories->total(), 1), 1) }}</div>
                            <div class="text-muted small">Avg per Category</div>
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
                            <div class="rounded-3 p-3" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-folder-open text-white fs-5"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-4">{{ \App\Models\Product::whereNull('category_id')->count() }}</div>
                            <div class="text-muted small">Uncategorized</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and View Toggle -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-body p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <div class="modern-search">
                                <input type="text" placeholder="Search categories..." class="form-control" 
                                       id="searchInput" value="{{ request('search') }}">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-modern active" id="gridView">
                                        <i class="fas fa-th me-1"></i> Grid
                                    </button>
                                    <button type="button" class="btn btn-outline-modern" id="listView">
                                        <i class="fas fa-list me-1"></i> List
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid View -->
    <div id="categoriesGrid" class="row">
        @forelse($categories as $category)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-3 category-item animate-fade-in" data-name="{{ strtolower($category->name) }}">
            <div class="modern-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-3 p-3" style="background-color: {{ $category->color }}20;">
                                <i class="{{ $category->icon ?? 'fas fa-tag' }} fs-4" style="color: {{ $category->color }};"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $category->name }}</h6>
                            <div class="small text-muted">{{ $category->products_count ?? $category->products->count() }} products</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('categories.edit', $category) }}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" 
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    @if($category->description)
                        <p class="text-muted small mb-3">{{ Str::limit($category->description, 80) }}</p>
                    @endif
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-box me-1"></i>
                                Products
                            </a>
                        </div>
                        <div class="badge rounded-pill" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                            {{ $category->products_count ?? $category->products->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tags fs-1 text-muted opacity-50 mb-3"></i>
                    <h5>No categories found</h5>
                    <p class="text-muted mb-3">Get started by creating your first category.</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary-modern">
                        <i class="fas fa-plus me-2"></i>
                        Add Category
                    </a>
                </div>
            </div>
        </div>
        @endforelse

        <!-- Add New Category Card -->
        @if($categories->count() > 0)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
            <div class="modern-card h-100 border-dashed">
                <div class="card-body p-3 d-flex align-items-center justify-content-center" style="min-height: 160px;">
                    <div class="text-center">
                        <div class="rounded-3 p-3 bg-light mb-3 mx-auto" style="width: fit-content;">
                            <i class="fas fa-plus fs-4 text-muted"></i>
                        </div>
                        <h6 class="mb-2">Add New Category</h6>
                        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary-modern">
                            Create Category
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Categories List View (Hidden by default) -->
    <div id="categoriesList" class="row" style="display: none;">
        <div class="col-12">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-list text-primary me-2"></i>
                        Categories List
                    </h6>
                    <p class="text-muted small mb-0">{{ $categories->total() }} categories found</p>
                </div>
                <div class="card-body p-0">
                    <div class="modern-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Products</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr class="category-item" data-name="{{ strtolower($category->name) }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-3 p-2" style="background-color: {{ $category->color }}20;">
                                                    <i class="{{ $category->icon ?? 'fas fa-tag' }}" style="color: {{ $category->color }};"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $category->name }}</div>
                                                <div class="small text-muted">ID: {{ $category->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $category->description ? Str::limit($category->description, 50) : 'No description' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
                                            {{ $category->products_count ?? $category->products->count() }} products
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ $category->created_at->format('M d, Y') }}</div>
                                            <div class="text-muted">{{ $category->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius);
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.border-dashed {
    border: 2px dashed #e2e8f0 !important;
}

.border-dashed:hover {
    border-color: #3b82f6 !important;
    background: rgba(59, 130, 246, 0.02);
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

.btn-group .btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.pagination .page-link {
    border: none;
    color: #6b7280;
    font-weight: 500;
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: var(--border-radius);
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
    background: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
</style>

<script>
let searchTimeout;

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Refresh functionality
function refreshPage() {
    const refreshBtn = document.querySelector('[onclick="refreshPage()"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// View toggle functionality
document.getElementById('gridView').addEventListener('click', function() {
    document.getElementById('categoriesGrid').style.display = 'flex';
    document.getElementById('categoriesList').style.display = 'none';
    this.classList.add('active');
    document.getElementById('listView').classList.remove('active');
});

document.getElementById('listView').addEventListener('click', function() {
    document.getElementById('categoriesGrid').style.display = 'none';
    document.getElementById('categoriesList').style.display = 'block';
    this.classList.add('active');
    document.getElementById('gridView').classList.remove('active');
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const searchTerm = this.value.toLowerCase();
        const categoryItems = document.querySelectorAll('.category-item');
        
        categoryItems.forEach(item => {
            const categoryName = item.getAttribute('data-name');
            if (categoryName.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }, 300);
});
</script>
@endsection 