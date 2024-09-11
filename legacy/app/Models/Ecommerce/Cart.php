<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {

	protected $table = 'ecommerce_carts';
	protected $fillable = ['customerId', 'date', 'downPayment', 'downPaymentWithVat', 'fixedDiscount', 'discountWithVat', 'percentageDiscount', 'billed', 'printDescriptions', 'printStatus', 'print_total', 'print_total', 'status'];
}
