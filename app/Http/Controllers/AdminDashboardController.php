<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalValue = Product::sum(DB::raw('price * quantity'));
        $lowStockProducts = Product::where('quantity', '<', 10)->count();
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalValue', 
            'lowStockProducts',
            'recentProducts'
        ));
    }
}