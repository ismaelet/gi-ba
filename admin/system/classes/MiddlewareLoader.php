<?php

class MiddlewareLoader {

	private static $middlewares = [];

	public static function __constructStatic() {
		$defaultMiddlewareLoader = [
			'public' => function () {
				global $user;
				$public = sys_config('static') ?: DB::find('value FROM config WHERE name=?', ['public']);
				if (!$public && !$user->admin) redirect('/offline');
			},

			'not-public' => function () {
				$public = sys_config('static') ?: DB::find('value FROM config WHERE name=?', ['public']);
				if ($public) redirect('/');
			},

			'wip' => function () {
				redirect('/wip');
			},

			'logged' => function () {
				if (!logged()) redirect('/login');
			},

			'not-logged' => function () {
				if (sys_config('static') || logged()) redirect(sys_config('logged-redirect') ?: '/');
			},

			'admin' => function () {
				global $user;
				if (!$user->admin) redirect('/login');
			},

			'not-admin' => function () {
				global $user;
				if ($user->admin) redirect(sys_config('logged-redirect') ?: '/');
			},
		];

		foreach ($defaultMiddlewareLoader as $middlewareName => $middlewareFunction) {
			self::add($middlewareName, $middlewareFunction);
		}
	}

	public static function add($middlewareName, $middlewareFunction) {
		self::$middlewares[$middlewareName] = $middlewareFunction;
	}

	public static function run($middlewareName) {
		$middlewareFunction = self::$middlewares[$middlewareName] ?? null;

		if ($middlewareFunction) {
			call_user_func_array($middlewareFunction, []);
		} else {
			exit('Access denied: ' . $middlewareName);
		}
	}
}
