<?php

use App\Http\Controllers\Ecommerce\CustomerController;

/**
 * Customers
 */
\Legacy\System\Classes\Route::add('/api/customers', function () {
	echo json_encode(CustomerController::get());
});
