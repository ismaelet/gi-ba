<?php

// select table

use function Ecommerce\format_price;

$add = true;
$index = 'id';
$tbl = 'ecommerce_products';

// handle search
$query['filter'] = "
	LOWER(name) LIKE CONCAT('%',?,'%')
";

// select fields
$fields = [
	['title' => 'ID', 'name' => 'id'],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Prezzo di default', 'name' => 'price', 'size' => 2],
	['title' => 'Vendite', 'name' => 'sales', 'size' => 2],

	['type' => 'btn'],
];

// buttons
$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];


function _expand_results(&$entry, $field, $key, $row) {
	if ($field['name'] == 'price') $entry['value'] = format_price($entry['value']);
}
