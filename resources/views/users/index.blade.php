@extends('layouts.app')

@section('header', 'User Management')

<style>
/* Modern User Management Styles */
.user-management-container {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    padding: 24px;
}

.page-header-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.page-subtitle {
    color: #64748b;
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 24px;
}

.action-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.modern-btn {
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    color: white;
}

.btn-secondary-modern {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-secondary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(100, 116, 139, 0.4);
    color: white;
}

/* Search Section */
.search-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.search-container {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-wrapper {
    flex: 1;
    min-width: 300px;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 16px 20px 16px 48px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 18px;
}

/* Table Styles */
.users-table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.table-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 20px 24px;
    border-bottom: 2px solid #e2e8f0;
}

.table-title {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 12px;
}

.table-title i {
    color: #3b82f6;
    font-size: 18px;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modern-table th {
    padding: 16px 24px;
    text-align: left;
    font-weight: 600;
    color: #475569;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

.modern-table td {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* User Avatar */
.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    color: white;
    margin-right: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.user-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 15px;
}

.user-created {
    font-size: 12px;
    color: #64748b;
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.status-inactive {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

/* Role Badges */
.role-badge {
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.role-admin {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.role-staff {
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
}

/* Action Buttons */
.action-buttons-cell {
    display: flex;
    gap: 8px;
}

.action-btn {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.btn-edit {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
}

.btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .user-management-container {
        padding: 16px;
    }
    
    .page-header-section {
        padding: 20px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .modern-btn {
        width: 100%;
        justify-content: center;
    }
    
    .search-container {
        flex-direction: column;
    }
    
    .search-input-wrapper {
        min-width: auto;
        width: 100%;
    }
    
    .modern-table {
        font-size: 14px;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 12px 16px;
    }
}
</style>
@endsection

@section('content')
<div class="user-management-container">
    <!-- Page Header -->
    <div class="page-header-section">
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Manage system users and their permissions</p>
        <div class="action-buttons">
            <button onclick="refreshPage()" class="modern-btn btn-secondary-modern">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </button>
            <a href="{{ route('admin.users.create') }}" class="modern-btn btn-primary-modern">
                <i class="fas fa-user-plus"></i>
                Add User
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" id="success-message">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" id="error-message">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Search Section -->
    <div class="search-section">
        <form method="GET" action="{{ route('admin.users.index') }}" class="search-container" id="search-form">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search users by name or email..." 
                       class="search-input"
                       oninput="debounceSearch()">
            </div>
            <button type="submit" class="modern-btn btn-primary-modern">
                <i class="fas fa-search"></i>
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="modern-btn btn-secondary-modern">
                    <i class="fas fa-times"></i>
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="users-table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-users"></i>
                Users ({{ $users->total() }})
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar" 
                                    style="background: linear-gradient(135deg, {{ '#' . substr(md5($user->name), 0, 6) }} 0%, {{ '#' . substr(md5($user->name), 6, 6) }} 100%)">
                                    <span>
                                        {{ substr($user->name, 0, 1) }}{{ substr($user->name, strpos($user->name, ' ') + 1, 1) }}
                                    </span>
                                </div>
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-created">Created {{ $user->created_at->format('m/d/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="role-badge role-admin">
                                    <i class="fas fa-shield-alt"></i> Admin
                                </span>
                            @else
                                <span class="role-badge role-staff">
                                    <i class="fas fa-user"></i> Staff
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->status == 'active')
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-sm text-gray-900">
                                {{ $user->last_login ? $user->last_login->format('m/d/Y, g:i:s A') : 'Never' }}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons-cell">
                                <button onclick="editUser({{ $user->id }})" class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4" id="modalTitle">Add New User</h3>
            <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="user_id" id="userId" value="">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" id="userName" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="userEmail" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                <div class="mb-4" id="passwordFields">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="userPassword"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password (when editing)</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" id="userRole"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="customer">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="userStatus"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let searchTimeout;
const users = @json($users->items());

// Auto-hide success/error messages
setTimeout(() => {
    const successMsg = document.getElementById('success-message');
    const errorMsg = document.getElementById('error-message');
    if (successMsg) successMsg.style.display = 'none';
    if (errorMsg) errorMsg.style.display = 'none';
}, 5000);

// Refresh page function
function refreshPage() {
    const refreshBtn = document.querySelector('[onclick="refreshPage()"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Debounced search
function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('search-form').submit();
    }, 500);
}

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New User';
    document.getElementById('userForm').action = route('admin.users.store');
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('userForm').reset();
    document.getElementById('userPassword').required = true;
    document.getElementById('userModal').classList.remove('hidden');
}

function editUser(id) {
    const user = users.find(u => u.id === id);
    if (user) {
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('userForm').action = route('admin.users.update', {id: id});
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('userId').value = id;
        
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').required = false;
        document.getElementById('userRole').value = user.role;
        document.getElementById('userStatus').value = user.status;
        
        document.getElementById('userModal').classList.remove('hidden');
    }
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('userModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Auto-refresh every 2 minutes for real-time updates
setInterval(() => {
    console.log('Auto-refreshing user data...');
    // In production, you'd make an AJAX call here instead of full reload
}, 120000);
</script>
@endsection 