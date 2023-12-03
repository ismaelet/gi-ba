<?php

namespace Ecommerce;

use Structure\DatabaseObject;

class Product {

	use DatabaseObject;

	public const TABLE = 'ecommerce_products';
	public const INDEX = 'id';

	/*
	* Get methods
	*/
	private function getIdCode() {
		return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
	}

	private function getFormattedPrice() {
		return format($this->price);
	}

	private function getPriceString() {
		return format_price($this->price);
	}

	/*
	* Set methods
	*/

	/*
	* Update methods
	*/

	/*
	* Instance methods
	*/

	/*
	* Render methods
	*/

	/*
	* Misc methods
	*/
}
