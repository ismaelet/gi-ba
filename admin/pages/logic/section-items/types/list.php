<?php

//select table
$tbl = 'items_types';
$index = 'id';

//handle search
$query['filter'] = "
	id=? OR
	name LIKE CONCAT('%',?,'%') OR
	names LIKE CONCAT('%',?,'%')
";

$rowLinks = 'add?id={{id}}';

//select fields
$fields = [
	['title' => 'ID', 'name' => 'id', 'size' => 1],
	['title' => 'Nome singular', 'name' => 'name', 'size' => 3],
	['title' => 'Nome plural', 'name' => 'names', 'size' => 3],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];
