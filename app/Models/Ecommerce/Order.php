<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

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
	protected $fillable = [];
}
