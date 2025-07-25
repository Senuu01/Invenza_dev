<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Customer;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Invoice;
use App\Models\CustomerActivity;
use App\Helpers\NumberHelper;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Basic statistics
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockItems = Product::where('quantity', '<=', 10)->count();
        $outOfStockItems = Product::where('quantity', '=', 0)->count();
        $totalValue = Product::sum(\DB::raw('price * quantity'));
        
        // Recent products
        $recentProducts = Product::with(['category', 'supplier'])
            ->latest()
            ->limit(5)
            ->get();
            
        // Low stock products
        $lowStockProducts = Product::with(['category', 'supplier'])
            ->where('quantity', '<=', 10)
            ->orderBy('quantity', 'asc')
            ->limit(8)
            ->get();
            
        // Out of stock products
        $outOfStockProducts = Product::with(['category', 'supplier'])
            ->where('quantity', '=', 0)
            ->latest()
            ->limit(5)
            ->get();
        
        // Products needing attention
        $productsNeedingAttention = Product::with(['category', 'supplier'])
            ->where('quantity', '<=', 10)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();
            
        // Recent stock updates by this user
        $myStockUpdates = CustomerActivity::with(['user'])
            ->where('user_id', $user->id)
            ->where('type', 'stock_update')
            ->latest()
            ->limit(5)
            ->get();
        
        // Categories summary
        $categoriesData = Category::withCount('products')
            ->get()
            ->map(function ($category) {
                $category->low_stock_count = Product::where('category_id', $category->id)
                    ->where('quantity', '<=', 10)
                    ->count();
                return $category;
            });

        // Recent activities for main dashboard
        $recentActivities = collect([
            [
                'type' => 'Product Added',
                'title' => 'New Product Added',
                'item' => $recentProducts->first() ? $recentProducts->first()->name : 'Product',
                'user' => $user->name,
                'time' => '2 hours ago'
            ],
            [
                'type' => 'Stock Updated',
                'title' => 'Stock Level Updated',
                'item' => $lowStockProducts->first() ? $lowStockProducts->first()->name : 'Product',
                'user' => $user->name,
                'time' => '4 hours ago'
            ],
            [
                'type' => 'Category Created',
                'title' => 'New Category Created',
                'item' => $categoriesData->first() ? $categoriesData->first()->name : 'Category',
                'user' => $user->name,
                'time' => '1 day ago'
            ],
            [
                'type' => 'Stock Updated',
                'title' => 'Inventory Checked',
                'item' => 'Low stock items identified',
                'user' => $user->name,
                'time' => '2 days ago'
            ]
        ]);

        // Check user role and return appropriate dashboard
        if ($user->role === 'admin') {
            // Admin can access both admin dashboard and main dashboard
            return view('dashboard', compact(
                'totalProducts',
                'totalCategories',
                'lowStockItems',
                'outOfStockItems',
                'totalValue',
                'recentProducts',
                'lowStockProducts',
                'outOfStockProducts',
                'productsNeedingAttention',
                'myStockUpdates',
                'categoriesData',
                'recentActivities'
            ))->with([
                'formattedTotalValue' => NumberHelper::formatCurrency($totalValue)
            ]);
        } elseif ($user->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        
        // Default to main dashboard for staff and other users
        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'lowStockItems',
            'outOfStockItems',
            'totalValue',
            'recentProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'productsNeedingAttention',
            'myStockUpdates',
            'categoriesData',
            'recentActivities'
        ))->with([
            'formattedTotalValue' => NumberHelper::formatCurrency($totalValue)
        ]);
    }
    
    public function getChartData(Request $request)
    {
        $days = $request->get('days', 7);
        $startDate = Carbon::now()->subDays($days);
        
        // Get categories with product counts for the specified period
        $categories = Category::with(['products' => function($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }])->get();
        
        $inventoryData = [
            'labels' => $categories->pluck('name')->toArray(),
            'inStock' => $categories->map(function($category) use ($startDate) {
                return $category->products()
                    ->where('status', 'in_stock')
                    ->where('created_at', '>=', $startDate)
                    ->count();
            })->toArray(),
            'lowStock' => $categories->map(function($category) use ($startDate) {
                return $category->products()
                    ->where('status', 'low_stock')
                    ->where('created_at', '>=', $startDate)
                    ->count();
            })->toArray()
        ];
        
        // Get stock status data for the specified period
        $stockData = [
            'inStock' => Product::where('status', 'in_stock')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'lowStock' => Product::where('status', 'low_stock')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'outOfStock' => Product::where('status', 'out_of_stock')
                ->where('created_at', '>=', $startDate)
                ->count()
        ];
        
        return response()->json([
            'inventory' => $inventoryData,
            'stock' => $stockData
        ]);
    }
}
