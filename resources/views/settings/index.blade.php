@extends('layouts.app')

@section('header', 'System Settings')

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
                                <i class="fas fa-cogs text-primary me-2"></i>
                                System Settings
                            </h4>
                            <p class="text-muted mb-0">Configure your application preferences and settings</p>
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

    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-list text-primary me-2"></i>
                        Settings Categories
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#notifications" class="list-group-item list-group-item-action settings-nav-item active" data-target="notifications">
                            <i class="fas fa-bell me-2"></i>
                            Notifications
                        </a>
                        <a href="#display" class="list-group-item list-group-item-action settings-nav-item" data-target="display">
                            <i class="fas fa-desktop me-2"></i>
                            Display & Format
                        </a>
                        <a href="#security" class="list-group-item list-group-item-action settings-nav-item" data-target="security">
                            <i class="fas fa-shield-alt me-2"></i>
                            Security
                        </a>
                        <a href="#system" class="list-group-item list-group-item-action settings-nav-item" data-target="system">
                            <i class="fas fa-server me-2"></i>
                            System Info
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="modern-card animate-fade-in animate-delay-2 mt-3">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; font-size: 1.5rem; color: white;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} fs-6">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <form method="post" action="{{ route('settings.update') }}" id="settingsForm">
                @csrf
                @method('patch')

                <!-- Notifications Settings -->
                <div class="settings-section" id="notifications-section">
                    <div class="modern-card animate-fade-in animate-delay-1 mb-4">
                        <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-bell text-primary me-2"></i>
                                Notification Preferences
                            </h5>
                            <p class="text-muted small mb-0">Configure how you want to receive notifications</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" 
                                               name="email_notifications" {{ $settings['notifications']['email_notifications'] ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="email_notifications">
                                            Email Notifications
                                        </label>
                                        <div class="text-muted small">Receive general notifications via email</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="low_stock_alerts" 
                                               name="low_stock_alerts" {{ $settings['notifications']['low_stock_alerts'] ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="low_stock_alerts">
                                            Low Stock Alerts
                                        </label>
                                        <div class="text-muted small">Get notified when products are running low</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="order_updates" 
                                               name="order_updates" {{ $settings['notifications']['order_updates'] ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="order_updates">
                                            Order Updates
                                        </label>
                                        <div class="text-muted small">Receive updates about order status changes</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="system_maintenance" 
                                               name="system_maintenance" {{ $settings['notifications']['system_maintenance'] ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="system_maintenance">
                                            System Maintenance
                                        </label>
                                        <div class="text-muted small">Get notified about system maintenance</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="settings-section d-none" id="display-section">
                    <div class="modern-card animate-fade-in animate-delay-1 mb-4">
                        <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-desktop text-primary me-2"></i>
                                Display & Format Settings
                            </h5>
                            <p class="text-muted small mb-0">Customize how information is displayed</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="items_per_page" class="form-label fw-semibold">Items Per Page</label>
                                    <select class="form-select" id="items_per_page" name="items_per_page">
                                        <option value="5" {{ $settings['display']['items_per_page'] == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ $settings['display']['items_per_page'] == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $settings['display']['items_per_page'] == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $settings['display']['items_per_page'] == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $settings['display']['items_per_page'] == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_format" class="form-label fw-semibold">Date Format</label>
                                    <select class="form-select" id="date_format" name="date_format">
                                        <option value="M d, Y" {{ $settings['display']['date_format'] == 'M d, Y' ? 'selected' : '' }}>Jan 01, 2024</option>
                                        <option value="d/m/Y" {{ $settings['display']['date_format'] == 'd/m/Y' ? 'selected' : '' }}>01/01/2024</option>
                                        <option value="Y-m-d" {{ $settings['display']['date_format'] == 'Y-m-d' ? 'selected' : '' }}>2024-01-01</option>
                                        <option value="d M Y" {{ $settings['display']['date_format'] == 'd M Y' ? 'selected' : '' }}>01 Jan 2024</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="time_format" class="form-label fw-semibold">Time Format</label>
                                    <select class="form-select" id="time_format" name="time_format">
                                        <option value="12h" {{ $settings['display']['time_format'] == '12h' ? 'selected' : '' }}>12 Hour (2:30 PM)</option>
                                        <option value="24h" {{ $settings['display']['time_format'] == '24h' ? 'selected' : '' }}>24 Hour (14:30)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="timezone" class="form-label fw-semibold">Timezone</label>
                                    <select class="form-select" id="timezone" name="timezone">
                                        <option value="UTC" {{ $settings['display']['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ $settings['display']['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                        <option value="America/Chicago" {{ $settings['display']['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                        <option value="America/Denver" {{ $settings['display']['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                        <option value="America/Los_Angeles" {{ $settings['display']['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                        <option value="Europe/London" {{ $settings['display']['timezone'] == 'Europe/London' ? 'selected' : '' }}>London</option>
                                        <option value="Europe/Paris" {{ $settings['display']['timezone'] == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                        <option value="Asia/Tokyo" {{ $settings['display']['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-section d-none" id="security-section">
                    <div class="modern-card animate-fade-in animate-delay-1 mb-4">
                        <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-shield-alt text-primary me-2"></i>
                                Security Settings
                            </h5>
                            <p class="text-muted small mb-0">Manage your account security preferences</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="two_factor_enabled" 
                                               name="two_factor_enabled" {{ $settings['security']['two_factor_enabled'] ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="two_factor_enabled">
                                            Two-Factor Authentication
                                        </label>
                                        <div class="text-muted small">Add an extra layer of security to your account</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="login_alerts" 
                                               name="login_alerts" {{ $settings['security']['login_alerts'] ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="login_alerts">
                                            Login Alerts
                                        </label>
                                        <div class="text-muted small">Get notified of new login attempts</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="session_timeout" class="form-label fw-semibold">Session Timeout (minutes)</label>
                                    <select class="form-select" id="session_timeout" name="session_timeout">
                                        <option value="15" {{ $settings['security']['session_timeout'] == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ $settings['security']['session_timeout'] == 30 ? 'selected' : '' }}>30 minutes</option>
                                        <option value="60" {{ $settings['security']['session_timeout'] == 60 ? 'selected' : '' }}>1 hour</option>
                                        <option value="120" {{ $settings['security']['session_timeout'] == 120 ? 'selected' : '' }}>2 hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="settings-section d-none" id="system-section">
                    <div class="modern-card animate-fade-in animate-delay-1 mb-4">
                        <div class="card-header bg-white border-0 px-4 pt-3 pb-2">
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-server text-primary me-2"></i>
                                System Information
                            </h5>
                            <p class="text-muted small mb-0">View system details and application information</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Application Version</label>
                                    <input type="text" class="form-control" value="1.0.0" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Laravel Version</label>
                                    <input type="text" class="form-control" value="{{ app()->version() }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">PHP Version</label>
                                    <input type="text" class="form-control" value="{{ PHP_VERSION }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Database</label>
                                    <input type="text" class="form-control" value="MySQL" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Environment</label>
                                    <input type="text" class="form-control" value="{{ app()->environment() }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Debug Mode</label>
                                    <input type="text" class="form-control" value="{{ config('app.debug') ? 'Enabled' : 'Disabled' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-save me-2"></i>
                        Save Settings
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

.form-label {
    color: #374151;
    margin-bottom: 8px;
}

.form-check-input:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.list-group-item-action {
    border: none;
    padding: 12px 16px;
    transition: all 0.2s ease;
}

.list-group-item-action:hover {
    background-color: #f8fafc;
    transform: translateX(4px);
}

.list-group-item-action.active {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.list-group-item-action.active:hover {
    background-color: #2563eb;
}

.alert {
    border: none;
    border-radius: var(--border-radius-lg);
}

.settings-section {
    transition: all 0.3s ease;
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

// Settings navigation
document.querySelectorAll('.settings-nav-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all nav items
        document.querySelectorAll('.settings-nav-item').forEach(nav => {
            nav.classList.remove('active');
        });
        
        // Add active class to clicked item
        this.classList.add('active');
        
        // Hide all sections
        document.querySelectorAll('.settings-section').forEach(section => {
            section.classList.add('d-none');
        });
        
        // Show target section
        const target = this.getAttribute('data-target');
        document.getElementById(target + '-section').classList.remove('d-none');
    });
});
</script>
@endsection 