<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;

class CustomerController extends Controller {

	public static function get(): array|false {
		$filter = $_GET['filter'];

		// $carts = Cart::join('addresses', 'carts.ID', '=', 'addresses.cartID')
		// 	->where('carts.status', '!=', 0)
		// 	->where(function ($query) use ($filter) {
		// 		$query->where('carts.ID', 'like', '%' . $filter . '%')
		// 			->orWhere('aname', 'like', '%' . $filter . '%');
		// 	})
		// 	->orderBy('carts.ID', 'desc')
		// 	->limit(10)
		// 	->select('carts.ID as id', 'addresses.aname as name')
		// 	->get();

		// if ($carts->count()) {
		// 	$results = [];

		// 	foreach ($carts as $cart) {
		// 		$results[] = [
		// 			'id' => $cart->id,
		// 			'name' => $cart->id . ' - ' . $cart->name
		// 		];
		// 	}

		// 	return $results;
		// }

		return false;
	}
}
