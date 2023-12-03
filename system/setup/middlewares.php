<?php

function load_middleware($name, $user, $view) {

	$middleware = false;

	if (file_exists($file = SERVER_ROOT . '/app/middlewares/' . $name . '.php')) $middleware = $file;
	else if (file_exists($file = SERVER_ROOT . '/system/middlewares/' . $name . '.php')) $middleware = $file;

	require_once($middleware);
}
