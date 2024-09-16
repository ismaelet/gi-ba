<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_carts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'customerId',
		'date',
		'downPayment',
		'downPaymentWithVat',
		'fixedDiscount',
		'discountWithVat',
		'percentageDiscount',
		'billed',
		'printDescriptions',
		'printStatus',
		'print_total',
		'print_total',
		'status'
	];

	/**
	 * Get the order items for the order.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	function order_items(): HasMany {
		return $this->hasMany(OrderItem::class);
	}

	/**
	 * Get the total quantity of the order's items.
	 * 
	 * @return int
	 */
	function quantity(): int {
		return $this->order_items->sum('quantity');
	}
}
