<?php

//skin types
$skins = [];
$skinsRes = DB::select('id, name FROM items_skins ORDER BY name ASC');

while ($skin = $skinsRes->fetch_assoc()) {
	$skins[] = [
		'type' => 'cbx',
		'name' => 'skin[]',
		'value' => $skin['id'],
		'text' => $skin['name'],
		'ignore' => true,
		'checked' => $item->skins && in_array($skin['id'], $item->skins),
		'size' => 1
	];
}

//uses
$uses = [];
$usesRes = DB::select('id, name FROM items_uses ORDER BY name ASC');

while ($use = $usesRes->fetch_assoc()) {
	$uses[] = [
		'type' => 'cbx',
		'name' => 'use[]',
		'value' => $use['id'],
		'text' => $use['name'],
		'ignore' => true,
		'checked' => $item->uses && in_array($use['id'], $item->uses),
		'size' => 1
	];
}

$itemInputs = [
	[
		['type' => 'lbl', 'text' => 'Nome'],
		[
			'name' => 'name',
			'text' => 'Nome',
			'value' => $item->id ? $item->name : '',
			'required' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Categoria do produto'],
		[
			'type' => 'select',
			'name' => 'categoryId',
			'text' => 'Categoria',
			'query' => '* FROM items_categories ORDER BY name ASC',
			'field-name' => 'id',
			'field-show' => 'name',
			'value' => $item->id ? $item->category->id : '',
			'empty' => true,
		],
	],
	[
		['type' => 'lbl', 'text' => 'Tipo de produto'],
		[
			'type' => 'select',
			'name' => 'typeId',
			'text' => 'Tipo de produto',
			'query' => '* FROM items_types ORDER BY name ASC',
			'field-name' => 'id',
			'field-show' => 'name',
			'value' => $item->id ? $item->typeId : '',

		],
	],
	[
		['type' => 'lbl', 'text' => 'Tipo de pele'],
		['type' => 'hidden', 'name' => 'skins'],
		...$skins
	],
	[
		['type' => 'lbl', 'text' => 'Preocupação'],
		['type' => 'hidden', 'name' => 'uses'],
		...$uses
	],
];
