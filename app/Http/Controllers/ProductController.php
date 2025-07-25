<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\StockUpdateNotification;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::where('status', 'active')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        
        // Auto-determine status based on quantity and low stock threshold
        $lowStockThreshold = $data['low_stock_threshold'] ?? 10;
        
        if ($data['quantity'] == 0) {
            $data['status'] = 'out_of_stock';
        } elseif ($data['quantity'] <= $lowStockThreshold) {
            $data['status'] = 'low_stock';
        } else {
            $data['status'] = 'in_stock';
        }
        
        // Set default low stock threshold if not provided
        if (!isset($data['low_stock_threshold'])) {
            $data['low_stock_threshold'] = 10;
        }
        
        Product::create($data);
        
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::where('status', 'active')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        
        // Store old quantity for notification
        $oldQuantity = $product->quantity;
        
        // Auto-determine status based on quantity and low stock threshold
        $lowStockThreshold = $data['low_stock_threshold'] ?? $product->low_stock_threshold ?? 10;
        
        if ($data['quantity'] == 0) {
            $data['status'] = 'out_of_stock';
        } elseif ($data['quantity'] <= $lowStockThreshold) {
            $data['status'] = 'low_stock';
        } else {
            $data['status'] = 'in_stock';
        }
        
        $product->update($data);
        
        // Send notification to admins if staff updated stock
        if (auth()->user()->isStaff() && $oldQuantity != $data['quantity']) {
            $this->sendStockUpdateNotification($product, $oldQuantity, $data['quantity']);
        }
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255'
        ]);

        $oldQuantity = $product->quantity;
        $newQuantity = $request->quantity;
        
        // Update product quantity
        $product->quantity = $newQuantity;
        
        // Update status based on quantity
        $lowStockThreshold = $product->low_stock_threshold ?? 10;
        if ($newQuantity == 0) {
            $product->status = 'out_of_stock';
        } elseif ($newQuantity <= $lowStockThreshold) {
            $product->status = 'low_stock';
        } else {
            $product->status = 'in_stock';
        }
        
        $product->save();

        // Send notification to admins if staff updated stock
        if (auth()->user()->isStaff()) {
            $this->sendStockUpdateNotification($product, $oldQuantity, $newQuantity);
        }

        // Log the stock update activity
        \App\Models\CustomerActivity::create([
            'customer_id' => null,
            'user_id' => auth()->id(),
            'type' => 'stock_update',
            'title' => 'Stock Updated',
            'description' => "Stock for {$product->name} updated from {$oldQuantity} to {$newQuantity}" . 
                           ($request->notes ? " - Notes: {$request->notes}" : ""),
            'metadata' => [
                'product_id' => $product->id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'notes' => $request->notes
            ]
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'product' => $product
            ]);
        }

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    /**
     * Send stock update notification to all admin users
     */
    private function sendStockUpdateNotification(Product $product, $oldQuantity, $newQuantity)
    {
        $adminUsers = User::where('role', 'admin')->get();
        $staffUser = auth()->user();
        
        foreach ($adminUsers as $admin) {
            $admin->notify(new StockUpdateNotification($product, $staffUser, $oldQuantity, $newQuantity));
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}