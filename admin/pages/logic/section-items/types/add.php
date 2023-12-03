<?php

//setup page
$tbl = 'items_types';
$index = 'id';
$index_show = 'name';

//form
$inputs = [
	[
		['type' => 'lbl', 'text' => 'Nome singular'],
		['name' => 'name', 'text' => 'Nome singular', 'required' => true]
	],
	[
		['type' => 'lbl', 'text' => 'Nome plural'],
		['name' => 'names', 'text' => 'Nome plural', 'required' => true]
	],
];

if (isset($_POST['name'])) $_POST['name'] = ucfirst(trim($_POST['name']));
if (isset($_POST['names'])) $_POST['names'] = ucfirst(trim($_POST['names']));
