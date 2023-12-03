<?php

class Route {

	private static $routes = [];

	public static function add($paths, $function) {

		$paths = (array) $paths;

		foreach ($paths as $path)
			array_push(self::$routes, [
				'path' => CLIENT_ROOT . $path,
				'function' => $function,
			]);
	}

	public static function run() {

		$path_found = false;
		$path = parse_url($_SERVER['REQUEST_URI'])['path'];

		foreach (self::$routes as $route) {
			if ((in_array($path, [$route['path'], $route['path'] . '/'])) || (strpos($route['path'], '*') && strpos($path, rtrim($route['path'], '/*')) !== false)) {
				$path_found = true;
				call_user_func_array($route['function'], [$path]);
				break;
			}
		}

		//error 404
		if (!$path_found) {
			View::initialize('errors/404');
			View::$title = 'Error 404';
			View::$template = 'void';
			View::$middlewares = [];
			View::show();
		}
	}
}
