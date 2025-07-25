@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600">Manage system users and their permissions</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="refreshPage()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </button>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-user-plus mr-2"></i>
                Add User
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" id="success-message">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" id="error-message">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4" id="search-form">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search users by name or email..." 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               oninput="debounceSearch()">
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Users ({{ $users->total() }})</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" 
                                    style="background-color: {{ '#' . substr(md5($user->name), 0, 6) }}">
                                    <span class="text-white font-medium">
                                        {{ substr($user->name, 0, 1) }}{{ substr($user->name, strpos($user->name, ' ') + 1, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">Created {{ $user->created_at->format('m/d/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role == 'admin')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-600 text-white">
                                    <i class="fas fa-shield-alt mr-1"></i> Admin
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-700">
                                    <i class="fas fa-user mr-1"></i> Staff
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status == 'active')
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    active
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $user->last_login ? $user->last_login->format('m/d/Y, g:i:s A') : 'Never' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <button onclick="editUser({{ $user->id }})" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
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