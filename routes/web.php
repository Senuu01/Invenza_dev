<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerNoteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeController;

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Error handling routes
Route::fallback(function (Request $request) {
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Route not found.',
            'error' => 'The requested resource could not be found.'
        ], 404);
    }
    
    return response()->view('errors.404', [
        'message' => 'The page you are looking for could not be found.',
        'suggestion' => 'Please check the URL or return to the homepage.'
    ], 404);
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard route - DashboardController handles role-based logic internally
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('admin')
        ->name('admin.dashboard');
    
    // Customer dashboard
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
        ->middleware('customer')
        ->name('customer.dashboard');
    
    // Profile management routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Settings routes with error handling
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::patch('/', [SettingsController::class, 'update'])->name('update');
    });
    
    // Core resource routes with role-based access control
    
    // Products - Staff can view and update stock, Admin can do everything
    Route::middleware(['staff'])->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::patch('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        Route::post('products/{product}/report-low-stock', [ProductController::class, 'reportLowStock'])->name('products.report-low-stock');
    });
    
    // Products admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
    
    // Suppliers - Admin only
    Route::middleware(['admin'])->group(function () {
        Route::resource('suppliers', SupplierController::class)->missing(function (Request $request) {
            return redirect()->route('suppliers.index')->withErrors(['error' => 'Supplier not found.']);
        });
    });
    
    // Categories - Staff can view, Admin can manage
    Route::middleware(['staff'])->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });
    
    Route::middleware(['admin'])->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
    
    // Customers - Admin only
    Route::middleware(['admin'])->group(function () {
        Route::resource('customers', CustomerController::class)->missing(function (Request $request) {
            return redirect()->route('customers.index')->withErrors(['error' => 'Customer not found.']);
        });
    });
    
    // CRM & Sales routes - Admin only
    Route::middleware(['admin'])->group(function () {
        Route::resource('proposals', ProposalController::class)->missing(function (Request $request) {
            return redirect()->route('proposals.index')->withErrors(['error' => 'Proposal not found.']);
        });
        
        Route::resource('invoices', InvoiceController::class)->missing(function (Request $request) {
            return redirect()->route('invoices.index')->withErrors(['error' => 'Invoice not found.']);
        });
    });
    
    // Enhanced Proposal specific routes with error handling - Admin only
    Route::middleware(['admin'])->group(function () {
        Route::prefix('proposals/{proposal}')->name('proposals.')->group(function () {
            Route::post('send', [ProposalController::class, 'send'])->name('send');
            Route::post('accept', [ProposalController::class, 'accept'])->name('accept');
            Route::post('reject', [ProposalController::class, 'reject'])->name('reject');
            Route::post('duplicate', [ProposalController::class, 'duplicate'])->name('duplicate');
            Route::get('pdf', [ProposalController::class, 'exportPdf'])->name('pdf');
            Route::get('convert-to-invoice', [ProposalController::class, 'convertToInvoice'])->name('convert-to-invoice');
        })->where('proposal', '[0-9]+');
        
        // Enhanced Invoice specific routes with error handling  
        Route::prefix('invoices/{invoice}')->name('invoices.')->group(function () {
            Route::post('send', [InvoiceController::class, 'send'])->name('send');
            Route::post('mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
            Route::post('duplicate', [InvoiceController::class, 'duplicate'])->name('duplicate');
            Route::get('pdf', [InvoiceController::class, 'exportPdf'])->name('pdf');
        })->where('invoice', '[0-9]+');
        
        // Customer notes - Admin only
        Route::resource('customer-notes', CustomerNoteController::class)
            ->except(['index', 'show'])
            ->missing(function (Request $request) {
                return redirect()->back()->withErrors(['error' => 'Customer note not found.']);
            });
        
        // Customer notes nested routes
        Route::prefix('customers/{customer}')->name('customers.')->group(function () {
            Route::get('notes', [CustomerNoteController::class, 'index'])->name('notes.index');
            Route::post('notes', [CustomerNoteController::class, 'store'])->name('notes.store');
            Route::get('product/{product}', [CustomerController::class, 'showProduct'])->name('product.show');
        })->where(['customer' => '[0-9]+', 'product' => '[0-9]+']);
    });
    
    // Admin routes with enhanced security
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::resource('users', UserController::class)->missing(function (Request $request) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'User not found.']);
        });
        
        // System settings
        Route::get('system-settings', [SettingsController::class, 'systemSettings'])->name('system.settings');
        Route::patch('system-settings', [SettingsController::class, 'updateSystemSettings'])->name('system.settings.update');
        
        // Reports and analytics
        Route::get('reports', function () {
            return view('admin.reports.index');
        })->name('reports.index');
        
        // Bulk operations
        Route::post('products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulk-update');
        Route::post('customers/bulk-export', [CustomerController::class, 'bulkExport'])->name('customers.bulk-export');
    });
    
    // Customer-specific routes
    Route::middleware(['customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('products', [CustomerController::class, 'products'])->name('products.index');
        Route::get('products/{product}', [CustomerController::class, 'showProduct'])->name('products.show');
        Route::get('orders', [CustomerController::class, 'orders'])->name('orders.index');
        Route::get('profile', [CustomerController::class, 'profile'])->name('profile.show');
    });
    
    // E-commerce routes for customers
Route::middleware(['customer'])->group(function () {
    // Shopping Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    
    // Stripe Checkout
    Route::post('/stripe/create-checkout-session', [StripeController::class, 'createCheckoutSession'])->name('stripe.create-checkout-session');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    Route::post('/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');
});
    
    // API routes for AJAX requests
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::get('suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');
        Route::get('featured-products', [ProductController::class, 'getFeaturedProducts'])->name('featured-products');
        
        // Dashboard chart data
        Route::get('dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
        
        // Notification routes
        Route::get('notifications/recent', [NotificationController::class, 'getRecentNotifications'])->name('notifications.recent');
        Route::get('notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
        Route::post('notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });
    
    // Notification routes
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Stock Requests - Staff can create, Admin can manage
    Route::middleware(['staff'])->group(function () {
        Route::resource('stock-requests', StockRequestController::class)->except(['destroy']);
    });
    
    Route::middleware(['admin'])->group(function () {
        Route::delete('stock-requests/{stockRequest}', [StockRequestController::class, 'destroy'])->name('stock-requests.destroy');
        Route::post('stock-requests/{stockRequest}/approve', [StockRequestController::class, 'approve'])->name('stock-requests.approve');
        Route::post('stock-requests/{stockRequest}/reject', [StockRequestController::class, 'reject'])->name('stock-requests.reject');
        Route::post('stock-requests/{stockRequest}/complete', [StockRequestController::class, 'complete'])->name('stock-requests.complete');
    });
});

require __DIR__.'/auth.php';