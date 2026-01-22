<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model {

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'net_product_price',
        'price',
        'status',
        'has_vat',
        'info',
    ];

    /**
     * Relationship: The order that owns the order item
     */
    function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
