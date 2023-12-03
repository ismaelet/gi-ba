<?php

namespace Ecommerce;

use DB;
use Structure\DatabaseObject;

class Customer {

	use DatabaseObject;

	public const TABLE = 'ecommerce_customers';
	public const INDEX = 'id';
	public const USER_INDEX = 'userId';

	public $id;
	public $name;
	public $phone;
	public $info;

	/*
	* Get methods
	*/
	private function getHasCart() {
		return (bool) $this->cart->id;
	}

	/*
	* Set methods
	*/

	/*
	* Update methods
	*/

	/*
	* Istance methods
	*/
	public static function new() {
		if (logged()) {
			$customerId = DB::find(self::INDEX . ' FROM ' . self::TABLE . ' WHERE ' . self::USER_INDEX . ' = ?', [userId()]);
		}

		if (!$customerId) {
			$customerId = DB::add(self::TABLE . ' (' . self::USER_INDEX . ')', [userId() ?: 0]);
		}

		return new self($customerId);
	}

	/*
	* Misc methods
	*/
}
