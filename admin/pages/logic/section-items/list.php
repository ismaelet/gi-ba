<?php

// select table
$tbl = 'items';
$index = 'id';

// handle search
$query['filter'] = "
	id=? OR
	name LIKE CONCAT('%',?,'%') OR
	typeId IN (SELECT id FROM items_types WHERE name LIKE CONCAT('%',?,'%'))
";

$rowLinks = 'add?id={{id}}';

// select fields
$fields = [
	['title' => '', 'type' => 'img', 'size' => 2, 'max-size' => 5],
	['title' => 'ID', 'name' => 'id', 'size' => 1],
	['title' => 'Nome', 'name' => 'name', 'size' => 3],
	['title' => 'Tipo', 'name' => 'typeId', 'type' => 'find', 'find' => 'name FROM item_types WHERE id=?', 'size' => 2],
	['title' => 'Prezzo', 'name' => 'price', 'size' => 2, 'parse' => 'float'],
	['title' => 'Aggiunto il', 'name' => 'time', 'parse' => 'date-day', 'size' => 2],
	//['title' => 'Em destaque', 'name' => 'highlighted', 'type' => 'custom', 'size' => 1],
	['title' => 'Attivo', 'name' => 'status', 'type' => 'custom', 'size' => 1],

	['type' => 'btn'],
];

$buttons = [
	['type' => 'edit'],
	['type' => 'delete'],
];

function _expand_results(&$entry, $field, $key, $row) {
	$item = new Item($row['id']);

	if ($field['type'] == 'img') $entry['src'] = file_public_url($item->image(400));
	else if ($field['name'] == 'price') $entry['value'] = formatc($entry['value']) . ' R$';
	else if ($field['name'] == 'highlighted') {
		$entry['content'] = $entry['value'] ? '<div class="list-icon active fill-gre icon-box">' . icon_embed('correct') . '</div>' : '<div class="list-icon inactive icon-box fill-7">' . icon_embed('close') . '</div>';
	} else if ($field['name'] == 'status') {
		$entry['content'] = $entry['value'] ? '<div class="list-icon active icon-box">' . icon_embed('correct') . '</div>' : '<div class="list-icon inactive icon-box fill-red">' . icon_embed('close') . '</div>';
	}
}
