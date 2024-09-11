<?php

//$orderCount = \Legacy\System\Classes\DB::count('ecommerce_carts');

use function Ecommerce\ecommerce_config;

$recapData = [];
$statuses = ecommerce_config('cart-statuses');

foreach ($statuses as $statusId => $status) {
	$statusData = [];

	$statusData['title'] = $status[1];
	$statusData['value'] = \Legacy\System\Classes\DB::sum('price FROM ecommerce_orders WHERE status = ?', [$statusId]);
	$statusData['slug'] = $status[0];

	$recapData[$statusId] = $statusData;
}

$withdrawnBilled = \Legacy\System\Classes\DB::sum('price FROM ecommerce_orders INNER JOIN ecommerce_carts ON ecommerce_orders.cartId = ecommerce_carts.id WHERE ecommerce_orders.status = ? AND billed = 1', [6]);
$withdrawnNotBilled = \Legacy\System\Classes\DB::sum('price FROM ecommerce_orders INNER JOIN ecommerce_carts ON ecommerce_orders.cartId = ecommerce_carts.id WHERE ecommerce_orders.status = ? AND billed = 0', [6]);
