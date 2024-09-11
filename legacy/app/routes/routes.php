<?php

//home
\Legacy\System\Classes\Route::add('/', function () {

	//create view
	$view = new \Legacy\System\Classes\View('home');

	//render view
	$view->show();
});

//gallery
\Legacy\System\Classes\Route::add('/gallery', function () {

	//create view
	$view = new \Legacy\System\Classes\View('gallery');

	//render view
	$view->show();
});

//collection
\Legacy\System\Classes\Route::add('/collection', function () {

	//create view
	$view = new \Legacy\System\Classes\View('collection');

	//render view
	$view->show();
});
