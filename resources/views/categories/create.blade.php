@extends('layouts.app')

@section('header', 'Add New Category')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="modern-card card-gradient mb-4 animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Add New Category
                            </h4>
                            <p class="text-muted mb-0">Create a new product category</p>
                        </div>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Categories
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category Form -->
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        
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
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Enter category name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="color" class="form-label fw-semibold">Category Color <span class="text-danger">*</span></label>
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#3b82f6') }}" required>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3"
                                          placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="icon" class="form-label fw-semibold">Icon Class</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-icons"></i>
                                    </span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', 'fas fa-tag') }}"
                                           placeholder="fas fa-tag">
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Use Font Awesome icon classes (e.g., fas fa-tag, fas fa-box, fas fa-laptop)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Preview</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 p-2 me-3" id="colorPreview" style="background-color: #3b82f6;">
                                            <i id="iconPreview" class="fas fa-tag text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold" id="namePreview">Category Name</div>
                                            <div class="small text-muted">Preview</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                                    <a href="{{ route('categories.index') }}" class="btn btn-outline-modern">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary-modern">
                                        <i class="fas fa-save me-2"></i>
                                        Create Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

.form-control-color {
    width: 100%;
    height: 48px;
    padding: 4px;
}

.input-group-text {
    border: 2px solid #e2e8f0;
    border-right: none;
    background: #f8fafc;
    font-weight: 600;
}

.form-control:focus + .input-group-text,
.input-group .form-control:focus {
    border-color: #3b82f6;
}

.form-label {
    color: #374151;
    margin-bottom: 8px;
}

.is-invalid {
    border-color: #ef4444 !important;
}

.invalid-feedback {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const colorInput = document.getElementById('color');
    const iconInput = document.getElementById('icon');
    const namePreview = document.getElementById('namePreview');
    const colorPreview = document.getElementById('colorPreview');
    const iconPreview = document.getElementById('iconPreview');

    // Update preview when inputs change
    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || 'Category Name';
    });

    colorInput.addEventListener('input', function() {
        colorPreview.style.backgroundColor = this.value;
    });

    iconInput.addEventListener('input', function() {
        const iconClasses = this.value || 'fas fa-tag';
        iconPreview.className = iconClasses + ' text-white';
    });
});
</script>
@endsection 