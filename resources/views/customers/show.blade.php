@extends('layouts.app')

@section('header', 'Customer Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
            <p class="text-gray-600">Customer Details and Information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Customer
            </a>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Customers
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Basic Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email Address</label>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone Number</label>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->phone ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                @if($customer->status == 'active')
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Address Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Address</label>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->address ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">City</label>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->city ?: 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Country</label>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->country ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            @if($customer->notes)
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $customer->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Credit Information Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Credit Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <label class="text-sm font-medium text-blue-700">Credit Limit</label>
                        <p class="text-2xl font-bold text-blue-900">
                            ${{ number_format($customer->credit_limit, 2) }}
                        </p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <label class="text-sm font-medium text-yellow-700">Current Balance</label>
                        <p class="text-2xl font-bold text-yellow-900">
                            ${{ number_format($customer->current_balance, 2) }}
                        </p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <label class="text-sm font-medium text-green-700">Available Credit</label>
                        <p class="text-2xl font-bold text-green-900">
                            ${{ number_format($customer->available_credit, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer Statistics Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Customer Statistics</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Customer Since</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $customer->created_at->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $customer->updated_at->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Total Days</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $customer->created_at->diffInDays(now()) }} days</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="mailto:{{ $customer->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Email
                    </a>
                    @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        Call Customer
                    </a>
                    @endif
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection