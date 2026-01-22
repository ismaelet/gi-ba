<?php

//select table

use Ecommerce\Coupon;

$index = 'id';
$tbl = 'ecommerce_coupons';

//handle search
$query['filter'] = "code LIKE CONCAT('%',?,'%')";

$rowLinks = 'add?id={{id}}';

//select fields
$fields = [
	['title' => 'ID', 'name' => 'id', 'size' => 1],
	['title' => 'Codice', 'name' => 'code', 'size' => 2],
	['title' => 'Filtro', 'name' => 'filter', 'size' => 3],
	['title' => 'Sconto %', 'name' => 'percentage', 'size' => 1],
	['title' => 'Sconto €', 'name' => 'flat', 'size' => 1],
	['title' => 'Data iniziale', 'name' => 'timeStart', 'size' => 2, 'parse' => 'date-day'],
	['title' => 'Data finale', 'name' => 'timeEnd', 'size' => 2, 'parse' => 'date-day'],
	['title' => 'Dettagli', 'name' => 'details', 'size' => 3],
	['title' => 'Usato', 'name' => 'count', 'size' => 1],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

//expand results
function _expand_results(&$entry, $field, $key, $row) {

	$coupon = new Ecommerce\Coupon($row['id']);

	if ($field['name'] == 'filter') {
		if (!empty($row['types'])) {
			$string = [];
			$types = explode(',', $row['types']);

			foreach ($types as $typeId) {
				$type = new Item\Type($typeId);
				$string[] = $type->names;
			}

			$entry['value'] = 'Applicato solo a ' . implode(', ', $string);
		} else $entry['value'] = '-';
	} else if ($field['name'] == 'percentage') {
		$entry['value'] = $entry['value'] ? $entry['value'] . '%' : '-';
	} else if ($field['name'] == 'flat') {
		$entry['value'] = $entry['value'] ? format_price($entry['value']) : '-';
	} else if ($field['name'] == 'details') {
		$info = [];

		if ($row['minPrice']) $info[] = 'prezzo minimo degli articoli: ' . format_price($row['minPrice']);
		if ($row['reusable']) $info[] = 'riusabile';
		if ($row['onePerUser']) $info[] = 'può essere usato solo una volta per cliente';
		if ($row['freeShipping']) $info[] = 'spese di spedizione gratis';

		$entry['value'] = empty($info) ? '-' : implode(', ', $info);
	} else if ($field['name'] == 'count') {
		$count = \Legacy\System\Classes\DB::count('ecommerce_carts WHERE couponId=? AND status<>0', [$row['code']]);
		$entry['value'] = $count . ' ' . ($count == 1 ? 'volta' : 'volte');
	}
}
