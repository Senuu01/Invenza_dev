@extends('layouts.app')

@section('header', 'Edit Customer')

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
                                <i class="fas fa-user-edit text-primary me-2"></i>
                                Edit Customer: {{ $customer->name }}
                            </h4>
                            <p class="text-muted mb-0">Update customer information below</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-modern">
                                <i class="fas fa-eye me-2"></i>
                                View Details
                            </a>
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Customers
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Form -->
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('customers.update', $customer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Basic Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
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
                                <label for="address" class="form-label fw-semibold">Street Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address', $customer->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-semibold">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $customer->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="form-label fw-semibold">Country</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country', $customer->country) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-credit-card text-primary me-2"></i>
                                    Financial Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="credit_limit" class="form-label fw-semibold">Credit Limit</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('credit_limit') is-invalid @enderror" 
                                           id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}" 
                                           step="0.01" min="0">
                                    @error('credit_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="current_balance" class="form-label fw-semibold">Current Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('current_balance') is-invalid @enderror" 
                                           id="current_balance" name="current_balance" value="{{ old('current_balance', $customer->current_balance) }}" 
                                           step="0.01" min="0">
                                    @error('current_balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-sticky-note text-primary me-2"></i>
                                    Additional Information
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label fw-semibold">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Any additional notes about this customer...">{{ old('notes', $customer->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Customer since {{ $customer->created_at->format('F j, Y') }}
                                        </small>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('customers.index') }}" class="btn btn-outline-modern">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary-modern">
                                            <i class="fas fa-save me-2"></i>
                                            Update Customer
                                        </button>
                                    </div>
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
@endsection 