<?php

/*
* Page configuration
*/

use Ecommerce\Order;
use Ecommerce\Product;

$pageConfig['free'] = true;
$pageConfig['parcial-title'] = 'Unifica il cliente';

/*
* Query configuration
*/
$queryConfig['table'] = 'ecommerce_customers';
$queryConfig['index-field'] = 'id';
$queryConfig['title-field'] = 'name';

/*
* Form
*/
$searchInputConfig = [
	'search' => 'customers',
	'placeholder' => 'Nome del cliente',
	'storedName' => 'id',
	'where' => ['id', '!=', $_GET['id']],
	'required' => true,
];

/*
* Events
*/
if (isset($_POST['submit'])) {
	// $productId = $_GET['id'];
	// $mainProductId = $_POST['id'];

	// DB::update(Order::TABLE . ' SET ' . Order::PRODUCT_INDEX . ' = ? WHERE ' . Order::PRODUCT_INDEX . ' = ?', [$mainProductId, $productId]);
	// DB::delete(Product::TABLE . ' WHERE ' . Product::INDEX . ' = ?', [$productId]);

	// redirect('list');
}
