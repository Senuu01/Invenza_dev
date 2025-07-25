@extends('layouts.app')

@section('header', 'Edit Category')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="modern-card card-gradient mb-4 animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-3 p-3" style="background-color: {{ $category->color }}20;">
                                    <i class="{{ $category->icon ?? 'fas fa-tag' }} fs-3" style="color: {{ $category->color }};"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold text-dark">Edit Category</h4>
                                <p class="text-muted mb-0">Update category information for "{{ $category->name }}"</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-modern">
                                <i class="fas fa-eye me-2"></i>
                                View Category
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Categories
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Category Edit Form -->
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('categories.update', $category) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Category Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $category->name) }}" required
                                       placeholder="Enter category name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="color" class="form-label fw-semibold">Category Color <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $category->color) }}" required>
                                    <input type="text" class="form-control" id="colorText" 
                                           value="{{ old('color', $category->color) }}" readonly>
                                </div>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="icon" class="form-label fw-semibold">Icon Class</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i id="iconPreview" class="{{ old('icon', $category->icon) ?? 'fas fa-tag' }}"></i>
                                    </span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $category->icon) }}" 
                                           placeholder="e.g., fas fa-tag">
                                </div>
                                <div class="form-text">
                                    Use FontAwesome icon classes. 
                                    <a href="https://fontawesome.com/icons" target="_blank" class="text-decoration-none">
                                        Browse icons <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Preview</label>
                                <div class="form-control d-flex align-items-center" style="height: auto; min-height: 38px;">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 p-2 me-3" id="categoryPreview" style="background-color: {{ old('color', $category->color) }}20;">
                                            <i id="categoryPreviewIcon" class="{{ old('icon', $category->icon) ?? 'fas fa-tag' }}" 
                                               style="color: {{ old('color', $category->color) }};"></i>
                                        </div>
                                        <span id="categoryPreviewName">{{ old('name', $category->name) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category Statistics -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-chart-bar text-primary me-2"></i>
                                    Category Statistics
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="modern-card bg-light">
                                    <div class="card-body text-center p-3">
                                        <div class="fw-bold fs-4 text-primary">{{ $category->products->count() }}</div>
                                        <div class="small text-muted">Total Products</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="modern-card bg-light">
                                    <div class="card-body text-center p-3">
                                        <div class="fw-bold fs-4 text-success">${{ number_format($category->products->sum('price'), 2) }}</div>
                                        <div class="small text-muted">Total Value</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="modern-card bg-light">
                                    <div class="card-body text-center p-3">
                                        <div class="fw-bold fs-4 text-info">{{ $category->products->sum('quantity') }}</div>
                                        <div class="small text-muted">Total Stock</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i>View Category
                                </a>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Update Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="modern-card border-danger animate-fade-in animate-delay-2">
                <div class="card-header bg-danger bg-opacity-10 border-danger">
                    <h6 class="mb-0 fw-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Danger Zone
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-danger mb-1">Delete Category</h6>
                            <p class="text-muted mb-0">
                                Permanently delete this category. All products will be uncategorized. This action cannot be undone.
                            </p>
                        </div>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="ms-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone. All products in this category will be uncategorized.')">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control-color {
    width: 60px;
    height: 38px;
    border-radius: var(--border-radius) 0 0 var(--border-radius) !important;
}

.border-dashed {
    border: 2px dashed #e2e8f0 !important;
}

.animate-delay-1 {
    animation-delay: 0.1s;
}

.animate-delay-2 {
    animation-delay: 0.2s;
}

.modern-card.bg-light {
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0;
}

.input-group-text {
    background-color: white;
    border-color: #e2e8f0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('colorText');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');
    const nameInput = document.getElementById('name');
    
    const categoryPreview = document.getElementById('categoryPreview');
    const categoryPreviewIcon = document.getElementById('categoryPreviewIcon');
    const categoryPreviewName = document.getElementById('categoryPreviewName');

    // Update color text when color changes
    colorInput.addEventListener('input', function() {
        colorText.value = this.value;
        updatePreview();
    });

    // Update icon preview when icon changes
    iconInput.addEventListener('input', function() {
        const iconClass = this.value || 'fas fa-tag';
        iconPreview.className = iconClass;
        updatePreview();
    });

    // Update name preview when name changes
    nameInput.addEventListener('input', function() {
        updatePreview();
    });

    function updatePreview() {
        const color = colorInput.value;
        const icon = iconInput.value || 'fas fa-tag';
        const name = nameInput.value || 'Category Name';

        // Update preview
        categoryPreview.style.backgroundColor = color + '20';
        categoryPreviewIcon.className = icon;
        categoryPreviewIcon.style.color = color;
        categoryPreviewName.textContent = name;
    }

    // Form submission with loading state
    document.getElementById('categoryForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        submitBtn.disabled = true;
        
        // Add loading class to form
        this.classList.add('loading');
    });

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection 