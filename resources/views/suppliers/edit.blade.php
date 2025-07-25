@extends('layouts.app')

@section('header', 'Edit Supplier')

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
                                <i class="fas fa-edit text-primary me-2"></i>
                                Edit Supplier
                            </h4>
                            <p class="text-muted mb-0">Update supplier information and details</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-outline-modern">
                                <i class="fas fa-eye me-2"></i>
                                View Supplier
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to List
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

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h5 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-building text-primary me-2"></i>
                        Supplier Information
                    </h5>
                    <p class="text-muted small mb-0">Update the supplier details below</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" id="supplierForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $supplier->name) }}" required
                                       placeholder="Enter company name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $supplier->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $supplier->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $supplier->email) }}" required
                                       placeholder="supplier@company.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}" required
                                       placeholder="+1 555-0123">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">Street Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="3" required
                                          placeholder="Enter complete street address">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="city" class="form-label fw-semibold">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city', $supplier->city) }}"
                                       placeholder="Enter city">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label fw-semibold">State/Province</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror"
                                       id="state" name="state" value="{{ old('state', $supplier->state) }}"
                                       placeholder="Enter state/province">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label fw-semibold">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror"
                                       id="country" name="country" value="{{ old('country', $supplier->country) }}"
                                       placeholder="Enter country">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Fields marked with <span class="text-danger">*</span> are required
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-modern">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>
                                    Update Supplier
                                </button>
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
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.invalid-feedback {
    display: block;
    font-size: 0.875rem;
    color: #dc2626;
    margin-top: 4px;
}

.is-invalid {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}
</style>

<script>
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

// Form submission with loading state
document.getElementById('supplierForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 6) {
        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    } else if (value.length >= 3) {
        value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
    }
    e.target.value = value;
});
</script>
@endsection 