<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {

    /**
     * Property: The attributes that are mass assignable
     */
    protected $fillable = [
        'customer_id',
        'date',
        'down_payment',
        'down_payment_with_vat',
        'fixed_discount',
        'discount_with_vat',
        'percentage_discount',
        'billed',
        'print_descriptions',
        'print_status',
        'print_total',
        'print_notes',
        'always_print_prices',
        'status',
    ];

    /**
     * Relationship: The order items for the order
     */
    function order_items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship: Get the customer that owns the order
     */
    function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the total quantity of the order's items
     */
    function quantity(): int {
        return $this->order_items->sum('quantity');
    }
}
