<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'type',
        'title',
        'description',
        'metadata',
        'related_type',
        'related_id'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public static function log($customerId, $type, $title, $description = null, $metadata = null, $related = null, $userId = null)
    {
        return static::create([
            'customer_id' => $customerId,
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'related_type' => $related ? get_class($related) : null,
            'related_id' => $related ? $related->id : null,
        ]);
    }
}
