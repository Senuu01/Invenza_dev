<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::with(['customer', 'user', 'items']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by customer
        if ($request->filled('customer')) {
            $query->where('customer_id', $request->customer);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('proposal_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $proposals = $query->orderBy('created_at', 'desc')->paginate(10);
        $customers = Customer::where('status', 'active')->get();
        
        return view('proposals.index', compact('proposals', 'customers'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        $products = Product::where('status', '!=', 'out_of_stock')->get();
        
        return view('proposals.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            $proposal = new Proposal($validated);
            $proposal->user_id = auth()->id();
            $proposal->proposal_number = $proposal->generateProposalNumber();
            $proposal->save();

            // Add proposal items
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                ProposalItem::create([
                    'proposal_id' => $proposal->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'description' => $itemData['description'] ?? $product->description,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
            }

            // Calculate totals
            $proposal->calculateTotals();

            // Log activity
            CustomerActivity::log(
                $proposal->customer_id,
                'proposal_created',
                'Proposal Created',
                "Proposal #{$proposal->proposal_number} was created",
                ['amount' => $proposal->total_amount],
                $proposal
            );

            DB::commit();

            return redirect()->route('proposals.show', $proposal)
                ->with('success', 'Proposal created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create proposal: ' . $e->getMessage()]);
        }
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['customer', 'user', 'items.product']);
        
        return view('proposals.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        if ($proposal->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft proposals can be edited.']);
        }

        $proposal->load(['items']);
        $customers = Customer::where('status', 'active')->get();
        $products = Product::where('status', '!=', 'out_of_stock')->get();
        
        return view('proposals.edit', compact('proposal', 'customers', 'products'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        if ($proposal->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft proposals can be updated.']);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'valid_until' => 'nullable|date|after:today',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            $proposal->update($validated);

            // Delete existing items and recreate
            $proposal->items()->delete();

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                ProposalItem::create([
                    'proposal_id' => $proposal->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'description' => $itemData['description'] ?? $product->description,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
            }

            $proposal->calculateTotals();

            CustomerActivity::log(
                $proposal->customer_id,
                'proposal_updated',
                'Proposal Updated',
                "Proposal #{$proposal->proposal_number} was updated",
                ['amount' => $proposal->total_amount],
                $proposal
            );

            DB::commit();

            return redirect()->route('proposals.show', $proposal)
                ->with('success', 'Proposal updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update proposal: ' . $e->getMessage()]);
        }
    }

    public function destroy(Proposal $proposal)
    {
        if ($proposal->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft proposals can be deleted.']);
        }

        $proposalNumber = $proposal->proposal_number;
        $customerId = $proposal->customer_id;
        
        $proposal->delete();

        CustomerActivity::log(
            $customerId,
            'proposal_deleted',
            'Proposal Deleted',
            "Proposal #{$proposalNumber} was deleted"
        );

        return redirect()->route('proposals.index')
            ->with('success', 'Proposal deleted successfully.');
    }

    public function send(Proposal $proposal)
    {
        if ($proposal->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft proposals can be sent.']);
        }

        $proposal->markAsSent();

        return back()->with('success', 'Proposal sent successfully.');
    }

    public function accept(Proposal $proposal)
    {
        if ($proposal->status !== 'sent') {
            return back()->withErrors(['error' => 'Only sent proposals can be accepted.']);
        }

        $proposal->markAsAccepted();

        return back()->with('success', 'Proposal accepted successfully.');
    }

    public function reject(Proposal $proposal)
    {
        if ($proposal->status !== 'sent') {
            return back()->withErrors(['error' => 'Only sent proposals can be rejected.']);
        }

        $proposal->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);

        CustomerActivity::log(
            $proposal->customer_id,
            'proposal_rejected',
            'Proposal Rejected',
            "Proposal #{$proposal->proposal_number} was rejected",
            ['amount' => $proposal->total_amount],
            $proposal
        );

        return back()->with('success', 'Proposal rejected.');
    }

    public function duplicate(Proposal $proposal)
    {
        DB::beginTransaction();
        
        try {
            $newProposal = $proposal->replicate();
            $newProposal->proposal_number = $newProposal->generateProposalNumber();
            $newProposal->status = 'draft';
            $newProposal->sent_at = null;
            $newProposal->accepted_at = null;
            $newProposal->rejected_at = null;
            $newProposal->save();

            // Duplicate items
            foreach ($proposal->items as $item) {
                $newItem = $item->replicate();
                $newItem->proposal_id = $newProposal->id;
                $newItem->save();
            }

            $newProposal->calculateTotals();

            DB::commit();

            return redirect()->route('proposals.show', $newProposal)
                ->with('success', 'Proposal duplicated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to duplicate proposal: ' . $e->getMessage()]);
        }
    }

    public function exportPdf(Proposal $proposal)
    {
        $proposal->load(['customer', 'user', 'items.product']);
        
        $pdf = PDF::loadView('proposals.pdf', compact('proposal'));
        
        return $pdf->download("proposal-{$proposal->proposal_number}.pdf");
    }

    public function convertToInvoice(Proposal $proposal)
    {
        if ($proposal->status !== 'accepted') {
            return back()->withErrors(['error' => 'Only accepted proposals can be converted to invoices.']);
        }

        return redirect()->route('invoices.create', ['proposal' => $proposal->id]);
    }
}
