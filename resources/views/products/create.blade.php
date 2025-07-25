@extends('layouts.app')

@section('header', 'Add New Product')

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
                                <i class="fas fa-plus text-primary me-2"></i>
                                Add New Product
                            </h4>
                            <p class="text-muted mb-0">Create a new product for your inventory</p>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Form -->
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('products.store') }}" method="POST" id="productForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Product Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Enter product name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="sku" class="form-label fw-semibold">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                       id="sku" name="sku" value="{{ old('sku') }}" required
                                       placeholder="e.g., WH-001">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3"
                                          placeholder="Enter product description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category and Supplier -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-tags text-primary me-2"></i>
                                    Category & Supplier
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="supplier_id" class="form-label fw-semibold">Supplier</label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id">
                                    <option value="">Select Supplier (Optional)</option>
                                    @foreach(\App\Models\Supplier::all() as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing and Inventory -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-dollar-sign text-primary me-2"></i>
                                    Pricing & Inventory
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="price" class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required
                                           step="0.01" min="0" placeholder="0.00">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="quantity" class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity') }}" required
                                       min="0" placeholder="0">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="low_stock_threshold" class="form-label fw-semibold">Low Stock Alert</label>
                                <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                       id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}"
                                       min="0" placeholder="10">
                                @error('low_stock_threshold')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Alert when stock falls below this number</small>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-cog text-primary me-2"></i>
                                    Additional Details
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="brand" class="form-label fw-semibold">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                       id="brand" name="brand" value="{{ old('brand') }}"
                                       placeholder="Enter brand name">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="model" class="form-label fw-semibold">Model</label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                       id="model" name="model" value="{{ old('model') }}"
                                       placeholder="Enter model number">
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="weight" class="form-label fw-semibold">Weight (kg)</label>
                                <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                       id="weight" name="weight" value="{{ old('weight') }}"
                                       step="0.01" min="0" placeholder="0.00">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="dimensions" class="form-label fw-semibold">Dimensions</label>
                                <input type="text" class="form-control @error('dimensions') is-invalid @enderror" 
                                       id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                                       placeholder="L x W x H (cm)">
                                @error('dimensions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-modern">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        Create Product
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
// Auto-generate SKU based on product name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const skuField = document.getElementById('sku');
    
    if (name && !skuField.value) {
        // Generate SKU from first letters of words + random number
        const words = name.split(' ');
        let sku = '';
        words.forEach(word => {
            if (word.length > 0) {
                sku += word.charAt(0).toUpperCase();
            }
        });
        sku += '-' + Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        skuField.value = sku;
    }
});

// Form submission with loading state
document.getElementById('productForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
    submitBtn.disabled = true;
});

// Auto-set low stock threshold based on quantity
document.getElementById('quantity').addEventListener('input', function() {
    const quantity = parseInt(this.value);
    const thresholdField = document.getElementById('low_stock_threshold');
    
    if (quantity && !thresholdField.value) {
        // Set threshold to 10% of quantity, minimum 5
        const threshold = Math.max(5, Math.floor(quantity * 0.1));
        thresholdField.value = threshold;
    }
});
</script>
@endsection