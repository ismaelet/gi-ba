<?php

use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Customer;

$id = $_GET['id'];

/*
* Page configuration
*/
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
	'exclude' => [$id],
	'required' => true,
];

/*
* Events
*/
if (isset($_POST['submit'])) {
	$customertId = $id;
	$mainCustomerId = $_POST['id'];

	Cart::where('customerId', $customertId)->update(['customerId' => $mainCustomerId]);
	Customer::where('id', $customertId)->delete();

	legacy_redirect('list');
}
