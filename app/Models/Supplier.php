<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'products_count',
        'status'
    ];

    /**
     * Get the products for the supplier.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
