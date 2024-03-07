<?php

use Ecommerce\Cart;
use Ecommerce\Customer;
use Ecommerce\Product;

$id = $_GET['id'] ?? false;
$redirectFilterStatus = $_GET['filter-status'] ?? false;
$newCart = (bool) !$id;

// return url
$returnUrl = 'list';

if ($id) {
	$listFilters = $_GET['list-filters'] ?? false;
	if ($listFilters) $returnUrl .= '?' . urldecode($listFilters);
	$returnUrl .= '#row' . $id;
}

// cart data
if (!$newCart) {
	$cart = new Cart($id);
	$customer = $cart->customer;
}

// search fields
$searchCustomer = [
	'search' => Customer::TABLE,
	'search-fields' => 'name',
	'index-field' => Customer::INDEX,
	'index-name' => 'customerId',
	'index-value' => $cart->customer->id ?? 0,
	'visible-field' => 'name',
	'visible-name' => 'customerName',
	'visible-value' => $cart->customer->name ?? '',
	'placeholder' => 'Nome completo',
	'has-title' => true,
	'required' => true
];

$searchProduct = [
	'search' => Product::TABLE,
	'search-fields' => 'name',
	'index-field' => Product::INDEX,
	'index-name' => 'productId[]',
	'visible-field' => 'name',
	'visible-name' => 'productName[]',
	'placeholder' => 'Nome del prodotto',
	'has-title' => true,
];

// form handling
if (isset($_POST['submit'])) {
	$cartStatus = false;
	$cartDate = $_POST['cartDate'];
	$customerId = $_POST['customerId'];
	$customerName = trim($_POST['customerName']);
	$customerPhone = $_POST['customerPhone'];
	$customerInfo = trim($_POST['customerInfo']);
	$downPayment = $_POST['downPayment'];
	$downPaymentWithVat = isset($_POST['downPaymentWithVat']) ? 1 : 0;
	$fixedDiscount = $_POST['fixedDiscount'];
	$discountWithVat = isset($_POST['discountWithVat']) ? 1 : 0;
	$percentageDiscount = $_POST['percentageDiscount'];
	$billed = isset($_POST['billed']) ? 1 : 0;
	$printDescriptions = isset($_POST['printDescriptions']) ? 1 : 0;
	$printStatus = isset($_POST['printStatus']) ? 1 : 0;

	/*
	* Customer
	*/
	if (!$customerId) {
		$customerId = DB::find('id FROM ecommerce_customers WHERE name = ?', [$customerName]);

		if (!$customerId) {
			$customerId = DB::add('ecommerce_customers (name)', [$customerName]);
		}
	}

	DB::update('ecommerce_customers SET phone = ?, info = ? WHERE id = ?', [$customerPhone, $customerInfo, $customerId]);

	/*
	* Cart
	*/
	if ($newCart) {
		$id = DB::add('ecommerce_carts ()');
	}

	DB::update('ecommerce_carts SET customerId = ?, date = ?, downPayment = ?, downPaymentWithVat = ?, fixedDiscount = ?, discountWithVat = ?, percentageDiscount = ?, billed = ?, printDescriptions = ?, printStatus = ? WHERE id = ?', [$customerId, $cartDate, $downPayment, $downPaymentWithVat, $fixedDiscount, $discountWithVat, $percentageDiscount, $billed, $printDescriptions, $printStatus, $id]);

	/*
	* Orders & products
	*/
	foreach ($_POST['productId'] as $productKey => $productId) {
		$orderId = $_POST['orderId'][$productKey];
		$orderInfo = $_POST['orderInfo'][$productKey];
		$orderQuantity = $_POST['orderQuantity'][$productKey];
		$orderPrice = $_POST['orderNetPrice'][$productKey] ?: 0;
		$orderTotalPrice = $_POST['orderTotalPrice'][$productKey] ?: 0;
		$orderStatus = $_POST['orderStatus'][$productKey];
		$orderVat = isset($_POST['orderVat' . ($productKey + 1)]) ? 1 : 0;
		$productName = trim($_POST['productName'][$productKey]);

		/*
		* Product
		*/
		// add a new product
		if (!$productId && $productName) {
			$productId = DB::find('id FROM ecommerce_products WHERE name = ?', [$productName]);

			if (!$productId) {
				$productId = DB::add('ecommerce_products (name, price)', [$productName, $orderPrice]);
			}
		}

		// update product price
		DB::update('ecommerce_products SET price = ? WHERE id = ?', [$orderPrice, $productId]);

		/*
		* Order
		*/
		// add a new order
		if (!$orderId) {
			$orderId = DB::add('ecommerce_orders (cartId)', [$id]);
		}

		// update the order
		DB::update("ecommerce_orders SET netProductPrice = ?, price = ?, productId = ?, quantity = ?, status = ?, hasVat = ?, info = ? WHERE id = ?", [$orderPrice, $orderTotalPrice, $productId, $orderQuantity, $orderStatus, $orderVat, $orderInfo, $orderId]);

		// delete orders with no products or quantity
		if (!$productId || !$orderQuantity) {
			DB::delete('ecommerce_orders WHERE id = ?', [$orderId]);
		} else if (!$cartStatus || $orderStatus < $cartStatus) {
			$cartStatus = $orderStatus;
		}
	}

	$cartStatus = $cartStatus ?: $_POST['cartStatus'];

	DB::update('ecommerce_carts SET status = ? WHERE id = ?', [$cartStatus, $id]);

	redirect($returnUrl);
}

// delete a cart
if (isset($_POST['delete'])) {
	DB::delete('ecommerce_carts WHERE id = ?', [$id]);
	DB::delete('ecommerce_orders WHERE cartId = ?', [$id]);

	redirect('list');
}

// delete a cart
if (isset($_POST['duplicate'])) {
	$newCart = $cart->duplicate();

	redirect('?id=' . $newCart->id);
}
