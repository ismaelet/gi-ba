<?php

//general redirect
function redirect($path = false) {
	
	if ($path) $url = $path[0] == '#' ? $path : __HROOT__.$path;
	else $url = $_SERVER['REQUEST_URI'];

	header('Location: '.$url);

	exit();
	
}