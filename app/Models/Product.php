<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'quantity',
        'category_id',
        'supplier_id',
        'image',
        'status',
        'low_stock_threshold',
        'weight',
        'dimensions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'dimensions' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if it's a web URL
            if (Str::startsWith($this->image, 'http')) {
                return $this->image; // Return web URL directly
            } else {
                return asset('storage/' . $this->image); // Return local file URL
            }
        }
        
        // Fallback to random web image
        return "https://picsum.photos/400/400?random=" . ($this->id ?? rand(1, 1000));
    }

    public function getIsLowStockAttribute()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->quantity <= 0;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'in_stock' => 'success',
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
            default => 'secondary'
        };
    }
}
