@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Invoice Details</h1>
            <p class="text-muted">{{ $invoice->invoice_number }} - {{ $invoice->customer->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Invoices
            </a>
            @if($invoice->status === 'draft')
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            @endif
        </div>
    </div>

    <div class="modern-card">
        <div class="card-body">
            <div class="text-center py-5">
                <i class="fas fa-file-invoice-dollar fa-4x text-primary mb-3"></i>
                <h3>Invoice #{{ $invoice->invoice_number }}</h3>
                <p class="text-muted">Status: <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($invoice->status) }}</span></p>
                <p class="text-muted">Amount: <strong>${{ number_format($invoice->total_amount, 2) }}</strong></p>
                <p class="text-muted">This detailed view is ready for implementation!</p>
                
                <div class="mt-4">
                    @if($invoice->status === 'draft')
                        <form action="{{ route('invoices.send', $invoice) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-paper-plane me-2"></i>Send to Customer
                            </button>
                        </form>
                    @endif
                    
                    @if($invoice->status === 'sent')
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#markPaidModal">
                            <i class="fas fa-check me-2"></i>Mark as Paid
                        </button>
                    @endif
                    
                    <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Invoice as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method">
                            <option value="">Select payment method</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Check">Check</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Reference</label>
                        <input type="text" class="form-control" name="payment_reference" placeholder="Transaction ID, Check #, etc.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 