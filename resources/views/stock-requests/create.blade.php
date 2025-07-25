@extends('layouts.app')

@section('header', 'Create Stock Request')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-card animate-fade-in">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-plus text-primary me-2"></i>
                        New Stock Request
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('stock-requests.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">Product <span class="text-danger">*</span></label>
                                <select class="form-select modern-select @error('product_id') is-invalid @enderror" 
                                        name="product_id" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Current Stock: {{ $product->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select modern-select @error('supplier_id') is-invalid @enderror" 
                                        name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', request('supplier_id')) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">Quantity Requested <span class="text-danger">*</span></label>
                                <input type="number" class="form-control modern-input @error('quantity_requested') is-invalid @enderror" 
                                       name="quantity_requested" value="{{ old('quantity_requested') }}" 
                                       min="1" required placeholder="Enter quantity">
                                @error('quantity_requested')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">Requested By</label>
                                <input type="text" class="form-control modern-input" 
                                       value="{{ auth()->user()->name }}" readonly>
                                <small class="text-muted">You are creating this request</small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Notes (Optional)</label>
                            <textarea class="form-control modern-textarea @error('notes') is-invalid @enderror" 
                                      name="notes" rows="4" 
                                      placeholder="Add any additional notes about this stock request...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('stock-requests.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Requests
                            </a>
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modern-input {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    background-color: #fff;
}

.modern-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.modern-select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    background-color: #fff;
}

.modern-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.modern-textarea {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    resize: vertical;
}

.modern-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-label {
    margin-bottom: 0.5rem;
}

.invalid-feedback {
    font-size: 0.875rem;
    color: #dc3545;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}
</style>
@endsection 