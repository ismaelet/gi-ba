<?php

global $id;

// plugins
plugin_jquery_ui();

// setup variables
$updated = $_GET['updated'] ?? false;
$added = $_GET['added'] ?? false;
$id = $_GET['id'] ?? false;
$item = new Item($id);

// page layout
$layout['delete'] = true;
$layout['save'] = true;
$layout['preview'] = $item->url;

$this->title = $item->id ? $item->name : 'Aggiungi un prodotto';

$images = DB::select('* FROM item_images WHERE itemId=? ORDER BY sort ASC', [$item->id]);

// template parts
include_once('-expands-add/basic-data.php');
include_once('-expands-add/advanced-data.php');
include_once('-expands-add/tech-data.php');

// form events
if (isset($_POST['submit-form'])) {

	// basic parsing
	$itemPrice = round(str_replace(',', '.', $_POST['price']), 2);
	//$discountPrice = round(str_replace(',', '.', $_POST['discountPrice']), 2);
	$itemHighlighted = $_POST['highlighted'] ?? 0;
	$itemSlug = !empty($_POST['slug']) ? $_POST['slug'] : generate_item_slug($_POST['name']);

	// create the item is it doesn't exist
	if (!$id) {
		$id = DB::add('items () VALUES ()');
		DB::add('item_data (itemId) VALUES (?)', [$id]);
		$added = true;
	}

	// update the item
	form_db_update('items', $itemInputs, 'id', $id);
	form_db_update('item_data', $itemData, 'itemId', $id);
	//form_db_update('items_data', $itemTech, 'itemId', $id);
	//form_db_update('items_data', $itemShippingTech, 'itemId', $id);

	DB::update(
		'items SET slug=?, highlighted=?, price=?, inStock=?, status=?, sku=? WHERE id=?',
		[$itemSlug, $itemHighlighted, $itemPrice, $_POST['inStock'], $_POST['status'], $_POST['sku'], $id]
	);

	// stocks
	DB::delete('item_stocks WHERE itemId=?', $id);
	DB::add('item_stocks (itemId, quantity)', [$id, $_POST['stock']]);

	// update the FAQs
	// add_faqs($id);

	// manage the variations
	/* $variations = '';
	$variationTitles = $_POST['variation-1'] ?? false;
	$variationVariants = $_POST['variation-1-variants'] ?? false;
	$variationPrices = $_POST['variation-1-prices'] ?? false;
	if ($variationVariants[0]) {
		$variations = $variationTitles . '=';
		$variants = [];
		foreach ($variationVariants as $key => $variant) {
			$price = (float) str_replace(',', '.', $variationPrices[$key]);
			$variants[] = $variant . ':' . $price;
		}
		$variations .= implode(',', $variants);
	}
	if (DB::exists('items_variations WHERE itemId=?', [$id]))
		DB::update('items_variations SET variations=? WHERE itemId=?', [$variations, $id]);
	else if ($variations)
		DB::add('items_variations (itemId, variations) VALUES (?, ?)', [$id, $variations]);*/

	// manage the imgs
	if (!empty($_FILES['new-img-files']['name'][0])) {
		// upload images
		item_upload_img();
	} else if (isset($_POST['images'])) {
		// reorder the images
		foreach ($_POST['images'] as $key => $img)
			DB::update('item_images SET sort=? WHERE id=?', [$key + 1, $img]);
	}

	// refresh
	redirect('?id=' . $id . '&' . ($added ? 'added' : 'updated') . '=1');
}

// delete
if (isset($_POST['delete'])) {
	$path = __PUBLIC_SERVER_ROOT__ . '/uploads/items/' . $id . '/*';
	$files = glob($path);

	DB::delete('items WHERE id=?', [$id]);

	foreach ($files as $file) unlink($file);

	redirect('list');
}

// add an img
if (isset($_POST['upload-img'])) {
	$imgId = $_POST['upload-img'][0];

	item_upload_img($imgId);

	redirect('#gallery');
}

// delete an img
if (isset($_POST['delete-img'])) {
	$imgId = $_POST['delete-img'][0];
	$path = __PUBLIC_SERVER_ROOT__ . '/uploads/items/' . $id . '/' . $imgId . '{-,.}*';
	$files = glob($path, GLOB_BRACE);

	foreach ($files as $file) unlink($file);

	DB::delete('item_images WHERE id=?', [$imgId]);

	redirect('#gallery');
}

// add a promotion
if (isset($_POST['add-promotion'])) {
	$promotionPrice = (float) str_replace(',', '.', $_POST['promotion-price']);
	$promotionStart = $_POST['promotion-start'];
	$promotionEnd = $_POST['promotion-end'];

	DB::delete('ecommerce_promotions WHERE itemId=?', [$id]);
	DB::add('ecommerce_promotions (itemId, price, start, end)', [$id, $promotionPrice, $promotionStart, $promotionEnd]);

	redirect('#promotions');
}

// delete promotion
if (isset($_POST['delete-promotion'])) {
	DB::delete('ecommerce_promotions WHERE itemId=?', $id);

	redirect('#promotions');
}

/*
* Functions
*/
// upload an image
function item_upload_img($imgId = false) {

	global $id;

	if ($imgId) {

		$sizes = [
			[1024, 90, 'min'],
			[400, 70, 'max'],
			[[100, 100], 70, 'crop'],
		];

		foreach ($sizes as $size) {
			$fileUrl = __PUBLIC_SERVER_ROOT__ . '/uploads/items/' . $id . '/' . $imgId . '-' . ($size[0][0] ?? $size[0]) . '.jpg';
			$fileConfig = [
				'url' => $fileUrl,
				'name' => 'img-file-' . $imgId,
				'size' => $size[0],
				'quality' => $size[1],
				'mode' => $size[2],
			];

			upload_img($fileConfig);
		}
	} else {

		foreach ($_FILES['new-img-files']['name'] as $key => $looping) {

			$sizes = [
				[1024, 90, 'min'],
				[400, 70, 'max'],
				[[100, 100], 70, 'crop'],
			];

			$imgId = DB::row('MAX(id) as maxId FROM item_images')['maxId'] + 1;

			foreach ($sizes as $size) {
				$fileUrl = __PUBLIC_SERVER_ROOT__ . '/uploads/items/' . $id . '/' . $imgId . '-' . ($size[0][0] ?? $size[0]) . '.jpg';
				$fileConfig = [
					'url' => $fileUrl,
					'name' => 'new-img-files',
					'size' => $size[0],
					'quality' => $size[1],
					'mode' => $size[2],
				];

				$fileConfig['key'] = $key;

				upload_img($fileConfig);
			}

			$sort = DB::row('MAX(sort) as maxSort FROM item_images WHERE itemId=?', [$id])['maxSort'] + 1;

			DB::add('item_images (id, itemId, sort) VALUES (?, ?, ?)', [$imgId, $id, $sort]);
		}
	}
}

// update faqs
/*function add_faqs($id) {
	$questions = $_POST['faq'];
	$answers = $_POST['faq-answer'];
	array_walk($questions, 'trim');
	array_walk($answers, 'trim');
	DB::delete('items_faqs WHERE itemId=?', $id);
	foreach ($questions as $key => $question) {
		if (!empty($question) && !empty($answers[$key])) {
			DB::add('items_faqs (itemId, question, answer) VALUES (?, ?, ?)', [$id, $question, $answers[$key]]);
		}
	}
}*/
