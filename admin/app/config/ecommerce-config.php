<?php

$ecommerceConfig = [
	// General settings
	'currency' => 'EUR',

	// Product
	'product-faq' => false,
	'product-installments' => false,

	// Shop
	'item-card-info' => false,
	'shop-sticky' => true,
	/*'filters' => [
		[
			'title' => 'Tipo di prodotto',
			'slug' => 'typeId',
			'list' => DB::select('id, name, names FROM item_types ORDER BY names ASC, name ASC'),
		],
		[
			'title' => 'Birrificio',
			'slug' => 'breweryId',
			'list' => DB::select('id, name FROM item_breweries ORDER BY name ASC'),
		],
		[
			'title' => 'Nazione',
			'slug' => 'nationalityId',
			'list' => DB::select('id, name FROM item_nationalities ORDER BY name ASC'),
			'sql' => 'breweryId IN (SELECT id FROM item_breweries WHERE nationalityId=?)',
		],
		[
			'title' => 'Stile',
			'slug' => 'styleId',
			'list' => DB::select('id, name FROM item_styles ORDER BY name ASC'),
		]
	],*/

	// Cart
	'cart-steps' => [
		1 => ['Accedi', 'login'],
		2 => ['Spedizione', 'address'],
		3 => ['Pagamento', 'payment'],
		4 => ['Confermato', ''],
	],

	'cart-statuses' => [
		'1' => ['to-order', 'Da ordinare', '#92e9ff', 'added'],
		'2' => ['added', 'Da fare', '#92e9ff', 'added'],
		'3' => ['in-cart', 'Nel carrello', '#92e9ff', 'added'],
		'4' => ['ordered', 'Ordinato', '#92e9ff', 'added'],
		'5' => ['prepared', 'Preparato', '#ffc677', 'prepared'],
		'6' => ['delivered', 'Ritirato', '#ff9600', 'delivered'],
		'7' => ['completed', 'Pagato', '#44c544', 'completed'],
		'8' => ['billed', 'Preventivato', '#44c544', 'billed'],
		'99' => ['canceled', 'Cancellato', '#92e9ff', 'canceled'],
	],

	// Payments
	'paypal-user' => 'pagamenti@beer-shop.it',
];
