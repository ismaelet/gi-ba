<?php

//item basic tech data
$itemTech = [
	[
		['type' => 'lbl', 'text' => 'Peso'],
		['name' => 'weight', 'text' => "Peso", 'value' => $item->weight],
	],
	[
		['type' => 'lbl', 'text' => 'Dimenções'],
		['name' => 'size', 'text' => "Dimenções (com. x alt. x lar.)", 'value' => $item->size],
	],
];

$itemShippingTech = [
	[
		['type' => 'lbl', 'text' => 'Peso'],
		['name' => 'shippingWeight', 'text' => "Peso", 'value' => $item->shippingWeight],
	],
	[
		['type' => 'lbl', 'text' => 'Dimenções'],
		['name' => 'shippingSize', 'text' => "Dimenções (com. x alt. x lar.)", 'value' => $item->shippingSize],
	],
];
