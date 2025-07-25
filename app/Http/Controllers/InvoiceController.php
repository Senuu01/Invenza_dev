<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Proposal;
use App\Models\CustomerActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'user', 'items']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }
        
        // Filter overdue
        if ($request->filled('overdue') && $request->overdue == '1') {
            $query->overdue();
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);
        $customers = Customer::where('status', 'active')->get();
        
        // Statistics for dashboard cards
        $stats = [
            'total' => Invoice::count(),
            'pending' => Invoice::whereIn('status', ['draft', 'sent'])->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::overdue()->count(),
            'total_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_amount' => Invoice::whereIn('status', ['sent'])->sum('total_amount'),
        ];
        
        return view('invoices.index', compact('invoices', 'customers', 'stats'));
    }

    public function create(Request $request)
    {
        $customers = Customer::where('status', 'active')->get();
        $products = Product::where('status', '!=', 'out_of_stock')->get();
        
        $proposal = null;
        if ($request->filled('proposal')) {
            $proposal = Proposal::with('items.product')->find($request->proposal);
        }
        
        return view('invoices.create', compact('customers', 'products', 'proposal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'proposal_id' => 'nullable|exists:proposals,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:today',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        // Validate stock availability
        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            if ($product->quantity < $itemData['quantity']) {
                return back()->withErrors([
                    'error' => "Insufficient stock for product: {$product->name}. Available: {$product->quantity}, Requested: {$itemData['quantity']}"
                ]);
            }
        }

        DB::beginTransaction();
        
        try {
            $invoice = new Invoice($validated);
            $invoice->user_id = auth()->id();
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->save();

            // Add invoice items
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'description' => $itemData['description'] ?? $product->description,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
            }

            // Calculate totals
            $invoice->calculateTotals();

            // Log activity
            CustomerActivity::log(
                $invoice->customer_id,
                'invoice_created',
                'Invoice Created',
                "Invoice #{$invoice->invoice_number} was created",
                ['amount' => $invoice->total_amount],
                $invoice
            );

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create invoice: ' . $e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'user', 'items.product', 'proposal']);
        
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft invoices can be edited.']);
        }

        $invoice->load(['items']);
        $customers = Customer::where('status', 'active')->get();
        $products = Product::where('status', '!=', 'out_of_stock')->get();
        
        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft invoices can be updated.']);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:today',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        // Validate stock availability
        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            if ($product->quantity < $itemData['quantity']) {
                return back()->withErrors([
                    'error' => "Insufficient stock for product: {$product->name}. Available: {$product->quantity}, Requested: {$itemData['quantity']}"
                ]);
            }
        }

        DB::beginTransaction();
        
        try {
            $invoice->update($validated);

            // Delete existing items and recreate
            $invoice->items()->delete();

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'description' => $itemData['description'] ?? $product->description,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
            }

            $invoice->calculateTotals();

            CustomerActivity::log(
                $invoice->customer_id,
                'invoice_updated',
                'Invoice Updated',
                "Invoice #{$invoice->invoice_number} was updated",
                ['amount' => $invoice->total_amount],
                $invoice
            );

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update invoice: ' . $e->getMessage()]);
        }
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft invoices can be deleted.']);
        }

        $invoiceNumber = $invoice->invoice_number;
        $customerId = $invoice->customer_id;
        
        $invoice->delete();

        CustomerActivity::log(
            $customerId,
            'invoice_deleted',
            'Invoice Deleted',
            "Invoice #{$invoiceNumber} was deleted"
        );

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function send(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft invoices can be sent.']);
        }

        $invoice->markAsSent();

        return back()->with('success', 'Invoice sent successfully.');
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        if ($invoice->status !== 'sent') {
            return back()->withErrors(['error' => 'Only sent invoices can be marked as paid.']);
        }

        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        try {
            $invoice->markAsPaid(
                $validated['payment_method'] ?? null,
                $validated['payment_reference'] ?? null
            );

            return back()->with('success', 'Invoice marked as paid and inventory has been updated.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to mark invoice as paid: ' . $e->getMessage()]);
        }
    }

    public function exportPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'user', 'items.product']);
        
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function duplicate(Invoice $invoice)
    {
        DB::beginTransaction();
        
        try {
            $newInvoice = $invoice->replicate();
            $newInvoice->invoice_number = $newInvoice->generateInvoiceNumber();
            $newInvoice->status = 'draft';
            $newInvoice->sent_at = null;
            $newInvoice->paid_at = null;
            $newInvoice->payment_method = null;
            $newInvoice->payment_reference = null;
            $newInvoice->inventory_reduced = false;
            $newInvoice->save();

            // Duplicate items
            foreach ($invoice->items as $item) {
                $newItem = $item->replicate();
                $newItem->invoice_id = $newInvoice->id;
                $newItem->save();
            }

            $newInvoice->calculateTotals();

            DB::commit();

            return redirect()->route('invoices.show', $newInvoice)
                ->with('success', 'Invoice duplicated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to duplicate invoice: ' . $e->getMessage()]);
        }
    }
}
