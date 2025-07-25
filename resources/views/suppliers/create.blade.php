@extends('layouts.app')

@section('header', 'Add New Supplier')

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
                                <i class="fas fa-truck text-primary me-2"></i>
                                Add New Supplier
                            </h4>
                            <p class="text-muted mb-0">Add a new supplier to your network</p>
                        </div>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Suppliers
                        </a>
                    </div>
                </div>
            </div>

            <!-- Supplier Form -->
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    Company Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Enter company name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-address-book text-primary me-2"></i>
                                    Contact Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required
                                       placeholder="supplier@company.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required
                                       placeholder="+1 (555) 123-4567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Address Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">Street Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" required
                                          placeholder="Enter street address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-semibold">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}"
                                       placeholder="Enter city">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label fw-semibold">State/Province</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state') }}"
                                       placeholder="Enter state">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label fw-semibold">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country') }}"
                                       placeholder="Enter country">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary-modern">
                                        <i class="fas fa-save me-2"></i>
                                        Create Supplier
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
@endsection 