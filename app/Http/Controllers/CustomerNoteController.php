<?php

namespace App\Http\Controllers;

use App\Models\CustomerNote;
use App\Models\Customer;
use App\Models\CustomerActivity;
use Illuminate\Http\Request;

class CustomerNoteController extends Controller
{
    public function index(Customer $customer)
    {
        $notes = $customer->customerNotes()->with('user')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('customers.notes.index', compact('customer', 'notes'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        
        return view('customer-notes.create', compact('customers'));
    }

    public function store(Request $request, Customer $customer = null)
    {
        $validated = $request->validate([
            'customer_id' => $customer ? 'nullable' : 'required|exists:customers,id',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:note,call,email,meeting,task',
            'priority' => 'required|in:low,medium,high',
            'is_private' => 'boolean',
            'scheduled_at' => 'nullable|date|after:now'
        ]);

        $customerId = $customer ? $customer->id : $validated['customer_id'];
        
        $note = CustomerNote::create([
            'customer_id' => $customerId,
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'priority' => $validated['priority'],
            'is_private' => $validated['is_private'] ?? false,
            'scheduled_at' => $validated['scheduled_at'] ?? null,
        ]);

        // Log activity
        CustomerActivity::log(
            $customerId,
            'note_added',
            'Note Added',
            "A new {$note->type} note was added: " . ($note->title ?: substr($note->content, 0, 50)),
            [
                'note_type' => $note->type,
                'priority' => $note->priority,
                'is_private' => $note->is_private
            ],
            $note
        );

        if ($customer) {
            return redirect()->route('customers.show', $customer)
                ->with('success', 'Note added successfully.');
        }

        return redirect()->route('customer-notes.index')
            ->with('success', 'Note created successfully.');
    }

    public function edit(CustomerNote $customerNote)
    {
        // Only allow editing own notes or if admin
        if ($customerNote->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->withErrors(['error' => 'You can only edit your own notes.']);
        }

        $customers = Customer::where('status', 'active')->get();
        
        return view('customer-notes.edit', compact('customerNote', 'customers'));
    }

    public function update(Request $request, CustomerNote $customerNote)
    {
        // Only allow editing own notes or if admin
        if ($customerNote->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->withErrors(['error' => 'You can only edit your own notes.']);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:note,call,email,meeting,task',
            'priority' => 'required|in:low,medium,high',
            'is_private' => 'boolean',
            'scheduled_at' => 'nullable|date|after:now'
        ]);

        $customerNote->update($validated);

        // Log activity
        CustomerActivity::log(
            $customerNote->customer_id,
            'note_updated',
            'Note Updated',
            "Note was updated: " . ($customerNote->title ?: substr($customerNote->content, 0, 50)),
            [
                'note_type' => $customerNote->type,
                'priority' => $customerNote->priority,
                'is_private' => $customerNote->is_private
            ],
            $customerNote
        );

        return back()->with('success', 'Note updated successfully.');
    }

    public function destroy(CustomerNote $customerNote)
    {
        // Only allow deleting own notes or if admin
        if ($customerNote->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return back()->withErrors(['error' => 'You can only delete your own notes.']);
        }

        $customerId = $customerNote->customer_id;
        $noteTitle = $customerNote->title ?: substr($customerNote->content, 0, 50);
        
        $customerNote->delete();

        // Log activity
        CustomerActivity::log(
            $customerId,
            'note_deleted',
            'Note Deleted',
            "Note was deleted: {$noteTitle}"
        );

        return back()->with('success', 'Note deleted successfully.');
    }
}
