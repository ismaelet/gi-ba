<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model {

    private const VAT = 0.22;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price_net',
        'unit_price_gross',
        'total_price_gross',
        'status',
        'has_vat',
        'info',
    ];

    /**
     * Attribute: Name of the order item (product name)
     */
    public function name(): Attribute {
        return Attribute::make(
            get: function () {
                return $this->product ? $this->product->name : 'N/A';
            },
        );
    }

    /**
     * Get the total net price of the order item
     */
    public function totalPriceNet(): Attribute {
        return Attribute::make(
            get: fn() => $this->unit_price_net * $this->quantity
        );
    }

    /**
     * Get the VAT amount for the order item
     */
    public function totalVat(): Attribute {
        return Attribute::make(
            get: fn() => $this->has_vat ? $this->total_price_net * self::VAT : 0
        );
    }

    /**
     * Relationship: The order that owns the order item
     */
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: The product that belongs to the order item
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the status name of the order
     */
    public function statusName(): string {
        return config('ecommerce.order_statuses')[$this->status]['label'] ?? 'Unknown';
    }
}
