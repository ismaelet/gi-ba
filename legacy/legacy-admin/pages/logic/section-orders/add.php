<?php

use App\Models\Action;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use Ecommerce\Cart as OldCart;
use Ecommerce\Customer;
use Ecommerce\Product;
use Illuminate\Support\Facades\Auth;

global $user;

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
	$oldCart = new OldCart($id);
	$cart = Cart::find($id);
	$customer = $oldCart->customer;
}

// search fields
$searchCustomer = [
	'search' => Customer::TABLE,
	'search-fields' => 'name',
	'index-field' => Customer::INDEX,
	'index-name' => 'customerId',
	'index-value' => $oldCart->customer->id ?? 0,
	'visible-field' => 'name',
	'visible-name' => 'customerName',
	'visible-value' => $oldCart->customer->name ?? '',
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
	$cartStatus = $_POST['cartStatus'];
	$oldCartDate = $_POST['cartDate'];
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
	$printTotal = isset($_POST['print_total']) ? 1 : 0;

	/*
	* Customer
	*/
	if (!$customerId) {
		$customerId = \Legacy\System\Classes\DB::find('id FROM ecommerce_customers WHERE name = ?', [$customerName]);

		if (!$customerId) {
			$customerId = \Legacy\System\Classes\DB::add('ecommerce_customers (name)', [$customerName]);
		}
	}

	\Legacy\System\Classes\DB::update('ecommerce_customers SET phone = ?, info = ? WHERE id = ?', [$customerPhone, $customerInfo, $customerId]);

	/*
	* Cart
	*/
	if ($newCart) {
		$id = \Legacy\System\Classes\DB::add('ecommerce_carts ()');
		$cart = Cart::find($id);
	}

	$cart->update([
		'customerId' => $customerId,
		'date' => $oldCartDate,
		'downPayment' => $downPayment,
		'downPaymentWithVat' => $downPaymentWithVat,
		'fixedDiscount' => $fixedDiscount,
		'discountWithVat' => $discountWithVat,
		'percentageDiscount' => $percentageDiscount,
		'billed' => $billed,
		'printDescriptions' => $printDescriptions,
		'printStatus' => $printStatus,
		'print_total' => $printTotal
	]);

	if ($newCart) {

		// Save the action
		Action::create([
			'user_id' => $user->id,
			'action' => 'Ha creato l\'ordine ' . $id,
		]);
	} else {

		// Save the action
		Action::create([
			'user_id' => $user->id,
			'action' => 'Ha modificato l\'ordine ' . $id,
		]);
	}

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
		// Add a new product
		if (!$productId && $productName) {
			$productId = \Legacy\System\Classes\DB::find('id FROM ecommerce_products WHERE name = ?', [$productName]);

			if (!$productId) {
				$productId = \Legacy\System\Classes\DB::add('ecommerce_products (name, price)', [$productName, $orderPrice]);
			}
		}

		// Update product price
		if ($productId) {
			\Legacy\System\Classes\DB::update('ecommerce_products SET price = ? WHERE id = ?', [$orderPrice, $productId]);
		}

		/*
		* Order
		*/
		if ($productId && $orderQuantity) {
			// If is a valid order
			$orderParameters = [
				'cartId' => $id,
				'productId' => $productId,
				'quantity' => $orderQuantity,
				'netProductPrice' => $orderPrice,
				'price' => $orderTotalPrice,
				'status' => $orderStatus,
				'hasVat' => $orderVat,
				'info' => $orderInfo
			];

			if (!$orderId) {
				// Add the order
				Order::create($orderParameters);
			} else {
				// Update the order
				Order::find($orderId)->update($orderParameters);
			}

			// Update the cart status if is not set or is higher than the order status
			if (!$cartStatus || $orderStatus < $cartStatus) {
				$cartStatus = $orderStatus;
			}
		} else if ($orderId) {
			// Delete the order if it's not valid
			Order::find($orderId)->delete();
		}
	}

	// Update the cart status
	$cart->update(['status' => $cartStatus]);

	legacy_redirect($returnUrl);
}

// delete a cart
if (isset($_POST['delete'])) {
	\Legacy\System\Classes\DB::delete('ecommerce_carts WHERE id = ?', [$id]);
	\Legacy\System\Classes\DB::delete('ecommerce_orders WHERE cartId = ?', [$id]);

	legacy_redirect('list');
}

// delete a cart
if (isset($_POST['duplicate'])) {
	$newCart = $oldCart->duplicate();

	legacy_redirect('?id=' . $newCart->id);
}
