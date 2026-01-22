<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model {

    /**
     * Property: The attributes that are mass assignable
     */
    protected $fillable = [
        'name',
        'price',
    ];

    /**
     * Relationship: The orders items for the product
     */
    function order_items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
}
