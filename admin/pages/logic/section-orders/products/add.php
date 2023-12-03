<?php

// setup page
$tbl = 'ecommerce_products';
$index = 'id';
$index_show = 'name';

// form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome'],
		['name' => 'name', 'text' => 'Nome'],
	],
	[
		['type' => 'lbl', 'text' => 'Prezzo di default'],
		['name' => 'price', 'text' => 'Prezzo di default', 'parse' => 'float'],
	],
];
