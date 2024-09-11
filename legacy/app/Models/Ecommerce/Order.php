<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

	protected $table = 'ecommerce_orders';
	protected $fillable = ['cartId', 'productId', 'quantity', 'netProductPrice', 'price', 'status', 'hasVat', 'info',];
}
