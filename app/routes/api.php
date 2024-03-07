<?php

use App\Http\Controllers\Ecommerce\CustomerController;

/**
 * Customers
 */
Route::add('/api/customers', function () {
	echo json_encode(CustomerController::get());
});
