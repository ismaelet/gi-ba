<?php

$response = [];
$table = 'ecommerce_products';
$showField = 'name';
$search = $_GET['search'] ?? '';
$search = strtolower(trim($search));

if ($search) {
	$results = \Legacy\System\Classes\DB::select("id, " . $showField . " FROM " . $table . " WHERE id=? OR LOWER(" . $showField . ") LIKE CONCAT('%',?,'%') ORDER BY " . $showField . " ASC LIMIT 10", [$search, $search]);

	while ($result = $results->fetch_assoc()) {
		$response[] = [
			'value-field' => $result['id'],
			'show-field' => $result[$showField],
		];
	}
}

echo json_encode($response);
