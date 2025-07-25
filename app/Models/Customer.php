<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'status',
        'credit_limit',
        'current_balance',
        'notes',
        'assigned_user_id',
        'lead_status',
        'tags'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'tags' => 'array'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function customerNotes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function activities()
    {
        return $this->hasMany(CustomerActivity::class)->orderBy('created_at', 'desc');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->current_balance;
    }

    public function getTotalPurchasesAttribute()
    {
        return $this->invoices()->where('status', 'paid')->sum('total_amount');
    }

    public function getLastPurchaseDateAttribute()
    {
        return $this->invoices()->where('status', 'paid')->latest()->first()?->paid_at;
    }

    public function scopeByLeadStatus($query, $status)
    {
        return $query->where('lead_status', $status);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_user_id', $userId);
    }

    public function scopeWithTags($query, $tags)
    {
        if (is_string($tags)) {
            $tags = [$tags];
        }

        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    public function addTag($tag)
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    public function removeTag($tag)
    {
        $tags = $this->tags ?? [];
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        $this->update(['tags' => array_values($tags)]);
    }

    public function logActivity($type, $title, $description = null, $metadata = null, $related = null)
    {
        return CustomerActivity::log(
            $this->id,
            $type,
            $title,
            $description,
            $metadata,
            $related
        );
    }
}
