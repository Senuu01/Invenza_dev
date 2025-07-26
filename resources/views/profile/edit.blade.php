@extends('layouts.app')

@section('header', 'Profile Settings')

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
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                Profile Settings
                            </h4>
                            <p class="text-muted mb-0">Manage your account information and preferences</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button onclick="refreshPage()" class="btn btn-outline-modern">
                                <i class="fas fa-sync-alt me-2"></i>
                                Refresh
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Messages -->
    @if (session('status') === 'profile-updated')
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show modern-card" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    Profile updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show modern-card" role="alert">
                    <i class="fas fa-shield-alt me-2"></i>
                    Password updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8 mb-4">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h5 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-user text-primary me-2"></i>
                        Profile Information
                    </h5>
                    <p class="text-muted small mb-0">Update your account's profile information and email address</p>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('profile.update') }}" id="profileForm">
                        @csrf
                        @method('patch')

                        <!-- Name -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Role and Status (Read-only) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Role</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-shield-alt"></i>
                                    </span>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                </div>
                                <small class="text-muted">Contact administrator to change your role</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Account Status</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-circle text-{{ $user->status === 'active' ? 'success' : 'danger' }}"></i>
                                    </span>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->status ?? 'active') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Account Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Member Since</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                    <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Verified</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-{{ $user->email_verified_at ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                                    </span>
                                    <input type="text" class="form-control" 
                                           value="{{ $user->email_verified_at ? 'Verified on ' . $user->email_verified_at->format('M d, Y') : 'Not verified' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Email Verification Notice -->
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-warning" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div class="flex-grow-1">
                                        <strong>Email verification required</strong>
                                        <p class="mb-0">Your email address is unverified. Please check your inbox for a verification link.</p>
                                    </div>
                                    <form method="post" action="{{ route('verification.send') }}" class="ms-3">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            Resend
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Save Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary-modern">
                                <i class="fas fa-save me-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Statistics & Quick Info -->
        <div class="col-lg-4 mb-4">
            <!-- User Avatar & Quick Info -->
            <div class="modern-card animate-fade-in animate-delay-2 mb-3">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; font-size: 2rem; color: white;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'customer' ? 'info' : 'primary') }} fs-6">
                        <i class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'customer' ? 'user' : 'user-tie') }} me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="modern-card animate-fade-in animate-delay-3 mb-3">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Account Statistics
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center g-3">
                        <div class="col-12 mb-2">
                            <div class="stats-item border-bottom pb-3">
                                <div class="fw-bold fs-4 text-primary mb-1">{{ \Carbon\Carbon::parse($user->created_at)->diffInDays() }}</div>
                                <div class="small text-muted">Days Active</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="stats-item">
                                <div class="fw-bold fs-6 text-success mb-1">{{ $user->updated_at->diffForHumans() }}</div>
                                <div class="small text-muted">Last Updated</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="modern-card animate-fade-in animate-delay-4">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="fas fa-key me-2"></i>
                            Change Password
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-success">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Go to Dashboard
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key text-primary me-2"></i>
                    Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="update_password_current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                               id="update_password_current_password" name="current_password" required>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="update_password_password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                               id="update_password_password" name="password" required>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                               id="update_password_password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-save me-2"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <strong>Warning!</strong> This action cannot be undone. All your data will be permanently deleted.
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Enter your password to confirm</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-modern" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Delete Account
                    </button>
                </div>
            </form>
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

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-content {
    border: none;
    border-radius: var(--border-radius-lg);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
    border-bottom: 1px solid #e2e8f0;
}

.modal-footer {
    border-top: 1px solid #e2e8f0;
}

/* Account Statistics Styling */
.stats-item {
    padding: 8px 0;
}

.stats-item .fw-bold {
    word-wrap: break-word;
    line-height: 1.2;
    margin-bottom: 4px;
}

.stats-item .small {
    font-size: 12px;
    line-height: 1.3;
}

/* Prevent text overflow */
.col-12 .stats-item {
    overflow: hidden;
}

.border-bottom {
    border-bottom: 1px solid #e2e8f0 !important;
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

    // Show password modal if there are errors
    @if ($errors->updatePassword->any())
        const passwordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        passwordModal.show();
    @endif

    // Show delete modal if there are errors
    @if ($errors->userDeletion->any())
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        deleteModal.show();
    @endif
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
</script>
@endsection
