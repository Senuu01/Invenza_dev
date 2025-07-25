@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Proposal Details</h1>
            <p class="text-muted">{{ $proposal->proposal_number }} - {{ $proposal->customer->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('proposals.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Proposals
            </a>
            @if($proposal->status === 'draft')
                <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
            @endif
        </div>
    </div>

    <div class="modern-card">
        <div class="card-body">
            <div class="text-center py-5">
                <i class="fas fa-file-contract fa-4x text-primary mb-3"></i>
                <h3>Proposal #{{ $proposal->proposal_number }}</h3>
                <p class="text-muted">Status: <span class="badge bg-{{ $proposal->status === 'accepted' ? 'success' : 'warning' }}">{{ ucfirst($proposal->status) }}</span></p>
                <p class="text-muted">Amount: <strong>${{ number_format($proposal->total_amount, 2) }}</strong></p>
                <p class="text-muted">This detailed view is ready for implementation!</p>
                
                <div class="mt-4">
                    @if($proposal->status === 'draft')
                        <form action="{{ route('proposals.send', $proposal) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-paper-plane me-2"></i>Send to Customer
                            </button>
                        </form>
                    @endif
                    
                    @if($proposal->status === 'sent')
                        <form action="{{ route('proposals.accept', $proposal) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success me-2">
                                <i class="fas fa-check me-2"></i>Accept Proposal
                            </button>
                        </form>
                        <form action="{{ route('proposals.reject', $proposal) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger me-2">
                                <i class="fas fa-times me-2"></i>Reject
                            </button>
                        </form>
                    @endif
                    
                    @if($proposal->status === 'accepted')
                        <a href="{{ route('proposals.convert-to-invoice', $proposal) }}" class="btn btn-success me-2">
                            <i class="fas fa-file-invoice me-2"></i>Convert to Invoice
                        </a>
                    @endif
                    
                    <a href="{{ route('proposals.pdf', $proposal) }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    
                    <form action="{{ route('proposals.duplicate', $proposal) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-copy me-2"></i>Duplicate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 