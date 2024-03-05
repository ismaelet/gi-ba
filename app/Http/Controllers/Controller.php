<?php

namespace App\Http\Controllers;

class Controller {

	protected static int|null $id;
	protected static array|null $body;

	public static function id(): int|null {
		if (!isset(static::$id)) {
			static::$id = explode_last('/', $_SERVER['REQUEST_URI']);

			if (is_numeric(static::$id)) {
				return static::$id = (int) static::$id;
			}
		}

		return static::$id;
	}

	public static function body(string|null $key = null) {
		if (!isset(static::$body)) {
			static::$body = json_decode(file_get_contents('php://input'), true) ?: [];
		}

		if ($key) {
			return static::$body[$key] ?? null;
		}

		return static::$body;
	}
}
