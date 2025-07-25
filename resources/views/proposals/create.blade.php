@extends('layouts.app')

@section('header', 'Create Proposal')

@section('content')
<div class="container-fluid px-2">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="modern-card card-gradient mb-4 animate-fade-in">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">
                                <i class="fas fa-file-contract text-primary me-2"></i>
                                Create Proposal
                            </h4>
                            <p class="text-muted mb-0">Create a new proposal for a customer</p>
                        </div>
                        <a href="{{ route('proposals.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Proposals
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Proposal Form -->
            <div class="modern-card animate-fade-in animate-delay-1">
                <div class="card-body p-4">
                    <form action="{{ route('proposals.store') }}" method="POST" id="proposalForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                                                 <h5 class="fw-bold text-dark mb-3">
                                     <i class="fas fa-info-circle text-primary me-2"></i>
                                     Basic Information
                                 </h5>
                             </div>
                         </div>
                        
                        <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Proposal Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title') }}" placeholder="Enter proposal title" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="valid_until" class="form-label">Valid Until</label>
                            <input type="date" class="form-control" id="valid_until" name="valid_until" 
                                   value="{{ old('valid_until') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('valid_until')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                   value="{{ old('tax_rate', 0) }}" min="0" max="100" step="0.01">
                            @error('tax_rate')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" 
                              placeholder="Enter proposal description">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Proposal Items -->
        <div class="modern-card mb-4">
            <div class="card-header card-gradient d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-list me-2"></i>Proposal Items
                </h5>
                <button type="button" class="btn btn-sm btn-light" onclick="addItem()">
                    <i class="fas fa-plus me-1"></i>Add Item
                </button>
            </div>
            <div class="card-body">
                <div id="itemsContainer">
                    <!-- Items will be added here dynamically -->
                </div>
                
                <!-- Totals Section -->
                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="totals-section">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotalDisplay">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="discount_amount" name="discount_amount" 
                                           value="{{ old('discount_amount', 0) }}" min="0" step="0.01" onchange="calculateTotals()">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span id="taxDisplay">$0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="totalDisplay">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="modern-card mb-4">
            <div class="card-header card-gradient">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-file-alt me-2"></i>Additional Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="terms_conditions" class="form-label">Terms & Conditions</label>
                            <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="4" 
                                      placeholder="Enter terms and conditions">{{ old('terms_conditions') }}</textarea>
                            @error('terms_conditions')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Internal Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" 
                                      placeholder="Enter internal notes (not visible to customer)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('proposals.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                <i class="fas fa-save me-2"></i>Create Proposal
            </button>
        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Fix scrolling white space issues */
.container-fluid {
    margin: 0 !important;
    padding: 0 8px !important;
    width: 100%;
    min-height: 100%;
    background: transparent;
}

.modern-card {
    margin-bottom: 1.5rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border: none;
}

/* Prevent row gaps */
.row {
    margin: 0 !important;
}

.row > * {
    padding-left: 8px;
    padding-right: 8px;
}

/* Item Rows */
.item-row {
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    background: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--card-shadow);
}

.item-row:hover {
    border-color: #3b82f6;
    box-shadow: var(--card-shadow-hover);
    transform: translateY(-2px);
}

/* Form Controls matching project style */
.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius);
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: white;
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.form-label {
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
    font-size: 14px;
}

/* Totals Section */
.totals-section {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--card-shadow);
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading .btn {
    position: relative;
}

.loading .btn::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
let itemCounter = 0;
const products = {!! json_encode($products->map(function($product) {
    return [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'price' => $product->price,
        'quantity' => $product->quantity,
        'description' => $product->description
    ];
})) !!};

document.addEventListener('DOMContentLoaded', function() {
    // Add first item by default
    addItem();
    
    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

function addItem() {
    itemCounter++;
    const container = document.getElementById('itemsContainer');
    
    const itemHtml = `
        <div class="item-row" id="item-${itemCounter}">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[${itemCounter}][product_id]" onchange="selectProduct(${itemCounter})" required>
                        <option value="">Select Product</option>
                        ${products.map(product => 
                            '<option value="' + product.id + '" data-price="' + product.price + '" data-name="' + product.name + '" data-sku="' + product.sku + '" data-description="' + product.description + '">' +
                                product.name + ' (' + product.sku + ') - $' + parseFloat(product.price).toFixed(2) +
                            '</option>'
                        ).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="items[${itemCounter}][quantity]" 
                           min="1" value="1" onchange="calculateItemTotal(${itemCounter})" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="items[${itemCounter}][unit_price]" 
                           min="0" step="0.01" onchange="calculateItemTotal(${itemCounter})" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Total</label>
                    <input type="text" class="form-control" id="item-total-${itemCounter}" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeItem(${itemCounter})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="items[${itemCounter}][description]" rows="2" 
                              placeholder="Item description (optional)"></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    calculateTotals();
}

function removeItem(itemId) {
    const itemElement = document.getElementById(`item-${itemId}`);
    if (itemElement) {
        itemElement.remove();
        calculateTotals();
    }
    
    // Ensure at least one item remains
    const remainingItems = document.querySelectorAll('.item-row').length;
    if (remainingItems === 0) {
        addItem();
    }
}

function selectProduct(itemId) {
    const select = document.querySelector(`#item-${itemId} select[name*="product_id"]`);
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        const description = selectedOption.getAttribute('data-description');
        
        // Set unit price
        const priceInput = document.querySelector(`#item-${itemId} input[name*="unit_price"]`);
        priceInput.value = parseFloat(price).toFixed(2);
        
        // Set description
        const descriptionTextarea = document.querySelector(`#item-${itemId} textarea[name*="description"]`);
        descriptionTextarea.value = description || '';
        
        calculateItemTotal(itemId);
    }
}

function calculateItemTotal(itemId) {
    const quantityInput = document.querySelector(`#item-${itemId} input[name*="quantity"]`);
    const priceInput = document.querySelector(`#item-${itemId} input[name*="unit_price"]`);
    const totalInput = document.getElementById(`item-total-${itemId}`);
    
    const quantity = parseFloat(quantityInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const total = quantity * price;
    
    totalInput.value = '$' + total.toFixed(2);
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    
    // Calculate subtotal from all items
    document.querySelectorAll('.item-row').forEach(item => {
        const quantityInput = item.querySelector('input[name*="quantity"]');
        const priceInput = item.querySelector('input[name*="unit_price"]');
        
        if (quantityInput && priceInput) {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            subtotal += quantity * price;
        }
    });
    
    // Get tax rate and discount
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    
    // Calculate tax and total
    const taxAmount = (subtotal - discount) * (taxRate / 100);
    const total = subtotal - discount + taxAmount;
    
    // Update displays
    document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('taxDisplay').textContent = '$' + taxAmount.toFixed(2);
    document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);
}

// Form submission with loading state
document.getElementById('proposalForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
    submitBtn.disabled = true;
    
    // Add loading class to form
    this.classList.add('loading');
});

// Tax rate change event
document.getElementById('tax_rate').addEventListener('change', calculateTotals);
</script>
@endsection 