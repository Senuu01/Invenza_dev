<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'proposal_id',
        'title',
        'description',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'due_date',
        'terms_conditions',
        'notes',
        'inventory_reduced',
        'sent_at',
        'paid_at',
        'payment_method',
        'payment_reference'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'inventory_reduced' => 'boolean',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function activities()
    {
        return $this->morphMany(CustomerActivity::class, 'related');
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();
    }

    public function generateInvoiceNumber()
    {
        $year = date('Y');
        $lastInvoice = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -4) + 1 : 1;
        
        return 'INV-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        CustomerActivity::log(
            $this->customer_id,
            'invoice_sent',
            'Invoice Sent',
            "Invoice #{$this->invoice_number} was sent to customer",
            ['amount' => $this->total_amount],
            $this
        );
    }

    public function markAsPaid($paymentMethod = null, $paymentReference = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference
        ]);

        // Reduce inventory if not already done
        if (!$this->inventory_reduced) {
            $this->reduceInventory();
        }

        CustomerActivity::log(
            $this->customer_id,
            'payment_received',
            'Payment Received',
            "Payment received for invoice #{$this->invoice_number}",
            [
                'amount' => $this->total_amount,
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentReference
            ],
            $this
        );
    }

    public function reduceInventory()
    {
        if ($this->inventory_reduced) {
            return false; // Already reduced
        }

        foreach ($this->items as $item) {
            $product = $item->product;
            
            if ($product->quantity < $item->quantity) {
                throw new \Exception("Insufficient stock for product: {$product->name}");
            }

            $product->decrement('quantity', $item->quantity);
            
            // Update product status based on new quantity
            if ($product->quantity == 0) {
                $product->update(['status' => 'out_of_stock']);
            } elseif ($product->quantity <= ($product->low_stock_threshold ?? 10)) {
                $product->update(['status' => 'low_stock']);
            }
        }

        $this->update(['inventory_reduced' => true]);

        CustomerActivity::log(
            $this->customer_id,
            'inventory_reduced',
            'Inventory Updated',
            "Inventory reduced for invoice #{$this->invoice_number}",
            ['items_count' => $this->items->count()],
            $this
        );

        return true;
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'paid';
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'paid');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
