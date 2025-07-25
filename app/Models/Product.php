<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description', 
        'price',
        'quantity',
        'stock',
        'status',
        'supplier_id',
        'category_id',
        'low_stock_threshold',
        'brand',
        'model',
        'weight',
        'dimensions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'weight' => 'decimal:2'
    ];

    /**
     * Get the supplier that owns the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the status label attribute.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'in_stock' => 'In Stock',
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock',
            default => 'Unknown'
        };
    }

    /**
     * Get the status color attribute for UI.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'in_stock' => 'success',
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Check if product is low stock based on threshold.
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= ($this->low_stock_threshold ?? 10);
    }

    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity == 0;
    }
}
