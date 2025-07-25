<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('quantity', '>', 0)
            ->paginate(12);
        
        $categories = \App\Models\Category::all();

        return view('customer.dashboard', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('customer.product-detail', compact('product'));
    }
}