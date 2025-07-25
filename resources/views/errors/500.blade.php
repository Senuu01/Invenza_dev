@extends('layouts.app')

@section('header', '500 - Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-yellow-500">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                500 - Server Error
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ $message ?? 'Something went wrong on our end.' }}
            </p>
            @if(isset($suggestion))
                <p class="mt-1 text-sm text-gray-500">
                    {{ $suggestion }}
                </p>
            @endif
        </div>
        
        <div class="flex flex-col space-y-4">
            <a href="{{ route('dashboard') }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                <i class="fas fa-home mr-2"></i>
                Return to Dashboard
            </a>
            
            <button onclick="location.reload()" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                <i class="fas fa-redo mr-2"></i>
                Try Again
            </button>
        </div>
        
        <div class="mt-8 text-xs text-gray-500">
            <p>Error Code: 500</p>
            <p>Timestamp: {{ now()->format('Y-m-d H:i:s') }}</p>
            <p>Reference ID: {{ Str::random(8) }}</p>
        </div>
    </div>
</div>

<style>
    .min-h-screen {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    
    .max-w-md {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection