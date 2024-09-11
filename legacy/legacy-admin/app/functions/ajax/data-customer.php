<?php

$customerId = $_GET['customerId'] ?? false;

if ($customerId) {
	$row = \Legacy\System\Classes\DB::row('info, phone FROM ecommerce_customers WHERE id=?', [$customerId]);
}

echo json_encode(
	[
		'info' => $row['info'],
		'phone' => $row['phone']
	]
);
