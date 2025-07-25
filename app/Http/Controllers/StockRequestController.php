<?php

namespace App\Http\Controllers;

use App\Models\StockRequest;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\StockRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = StockRequest::with(['supplier', 'product', 'requestedBy', 'approvedBy']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }
        
        // Filter by product
        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }
        
        // Role-based filtering
        if (Auth::user()->isStaff()) {
            $query->where('requested_by', Auth::id());
        }
        
        $stockRequests = $query->latest()->paginate(15);
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::all();
        
        return view('stock-requests.index', compact('stockRequests', 'suppliers', 'products'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::all();
        
        return view('stock-requests.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity_requested' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $stockRequest = StockRequest::create([
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'requested_by' => Auth::id(),
            'quantity_requested' => $request->quantity_requested,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        // Notify admins about new stock request
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new StockRequestNotification($stockRequest, 'new_request'));
        }

        return redirect()->route('stock-requests.index')
            ->with('success', 'Stock request created successfully and sent to admin for approval.');
    }

    public function show(StockRequest $stockRequest)
    {
        $stockRequest->load(['supplier', 'product', 'requestedBy', 'approvedBy']);
        
        // Check if user has permission to view this request
        if (Auth::user()->isStaff() && $stockRequest->requested_by !== Auth::id()) {
            abort(403, 'You can only view your own stock requests.');
        }
        
        return view('stock-requests.show', compact('stockRequest'));
    }

    public function edit(StockRequest $stockRequest)
    {
        // Only allow editing if request is pending and user is the requester or admin
        if ($stockRequest->status !== 'pending' || 
            (Auth::user()->isStaff() && $stockRequest->requested_by !== Auth::id())) {
            abort(403, 'You cannot edit this stock request.');
        }
        
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::all();
        
        return view('stock-requests.edit', compact('stockRequest', 'suppliers', 'products'));
    }

    public function update(Request $request, StockRequest $stockRequest)
    {
        // Only allow updating if request is pending and user is the requester or admin
        if ($stockRequest->status !== 'pending' || 
            (Auth::user()->isStaff() && $stockRequest->requested_by !== Auth::id())) {
            abort(403, 'You cannot update this stock request.');
        }
        
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity_requested' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $stockRequest->update([
            'supplier_id' => $request->supplier_id,
            'product_id' => $request->product_id,
            'quantity_requested' => $request->quantity_requested,
            'notes' => $request->notes
        ]);

        return redirect()->route('stock-requests.index')
            ->with('success', 'Stock request updated successfully.');
    }

    public function destroy(StockRequest $stockRequest)
    {
        // Only allow deletion if request is pending and user is the requester or admin
        if ($stockRequest->status !== 'pending' || 
            (Auth::user()->isStaff() && $stockRequest->requested_by !== Auth::id())) {
            abort(403, 'You cannot delete this stock request.');
        }
        
        $stockRequest->delete();
        
        return redirect()->route('stock-requests.index')
            ->with('success', 'Stock request deleted successfully.');
    }

    // Admin approval methods
    public function approve(Request $request, StockRequest $stockRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can approve stock requests.');
        }
        
        if ($stockRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $stockRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        // Notify the requester
        $stockRequest->requestedBy->notify(new StockRequestNotification($stockRequest, 'approved'));

        return redirect()->back()->with('success', 'Stock request approved successfully.');
    }

    public function reject(Request $request, StockRequest $stockRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can reject stock requests.');
        }
        
        if ($stockRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        $stockRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        // Notify the requester
        $stockRequest->requestedBy->notify(new StockRequestNotification($stockRequest, 'rejected'));

        return redirect()->back()->with('success', 'Stock request rejected successfully.');
    }

    public function complete(StockRequest $stockRequest)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only admins can complete stock requests.');
        }
        
        if ($stockRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved requests can be completed.');
        }

        // Update product stock
        $product = $stockRequest->product;
        $product->quantity += $stockRequest->quantity_requested;
        
        // Update product status
        $lowStockThreshold = $product->low_stock_threshold ?? 10;
        if ($product->quantity == 0) {
            $product->status = 'out_of_stock';
        } elseif ($product->quantity <= $lowStockThreshold) {
            $product->status = 'low_stock';
        } else {
            $product->status = 'in_stock';
        }
        
        $product->save();

        $stockRequest->update([
            'status' => 'completed'
        ]);

        // Notify the requester
        $stockRequest->requestedBy->notify(new StockRequestNotification($stockRequest, 'completed'));

        return redirect()->back()->with('success', 'Stock request completed and inventory updated successfully.');
    }
}
