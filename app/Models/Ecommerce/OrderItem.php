<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ecommerce_orders';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'cartId',
		'productId',
		'quantity',
		'netProductPrice',
		'price',
		'status',
		'hasVat',
		'info',
	];
}
