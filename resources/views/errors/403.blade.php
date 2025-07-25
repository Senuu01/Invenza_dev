@extends('layouts.app')

@section('header', '403 - Access Denied')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-red-500">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                403 - Access Denied
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ $message ?? 'You do not have permission to access this page.' }}
            </p>
            @if(isset($suggestion))
                <p class="mt-1 text-sm text-gray-500">
                    {{ $suggestion }}
                </p>
            @endif
        </div>
        
        <div class="flex flex-col space-y-4">
            <a href="{{ route('dashboard') }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                <i class="fas fa-home mr-2"></i>
                Return to Dashboard
            </a>
            
            <button onclick="history.back()" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </button>
        </div>
        
        <div class="mt-8 text-xs text-gray-500">
            <p>Error Code: 403</p>
            <p>Timestamp: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>

<style>
    .min-h-screen {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .max-w-md {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection