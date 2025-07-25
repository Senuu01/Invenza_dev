@extends('layouts.app')

@section('header', '404 - Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-blue-500">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.137 0-4.146-.832-5.657-2.343M6.343 6.343A7.962 7.962 0 0112 3c2.137 0 4.146.832 5.657 2.343m0 0L21 8.586M3 21L6.343 17.657"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                404 - Page Not Found
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ $message ?? 'The page you are looking for could not be found.' }}
            </p>
            @if(isset($suggestion))
                <p class="mt-1 text-sm text-gray-500">
                    {{ $suggestion }}
                </p>
            @endif
        </div>
        
        <div class="flex flex-col space-y-4">
            <a href="{{ route('dashboard') }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-home mr-2"></i>
                Return to Dashboard
            </a>
            
            <button onclick="history.back()" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </button>
        </div>
        
        <div class="mt-8 text-xs text-gray-500">
            <p>Error Code: 404</p>
            <p>Timestamp: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>

<style>
    .min-h-screen {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .max-w-md {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
</style>
@endsection