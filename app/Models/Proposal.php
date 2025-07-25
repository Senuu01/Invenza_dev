<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_number',
        'customer_id',
        'user_id',
        'title',
        'description',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'valid_until',
        'terms_conditions',
        'notes',
        'sent_at',
        'accepted_at',
        'rejected_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ProposalItem::class);
    }

    public function activities()
    {
        return $this->morphMany(CustomerActivity::class, 'related');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();
    }

    public function generateProposalNumber()
    {
        $year = date('Y');
        $lastProposal = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastProposal ? (int)substr($lastProposal->proposal_number, -4) + 1 : 1;
        
        return 'PROP-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        CustomerActivity::log(
            $this->customer_id,
            'proposal_sent',
            'Proposal Sent',
            "Proposal #{$this->proposal_number} was sent to customer",
            ['amount' => $this->total_amount],
            $this
        );
    }

    public function markAsAccepted()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        CustomerActivity::log(
            $this->customer_id,
            'proposal_accepted',
            'Proposal Accepted',
            "Proposal #{$this->proposal_number} was accepted by customer",
            ['amount' => $this->total_amount],
            $this
        );
    }

    public function isExpired()
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
