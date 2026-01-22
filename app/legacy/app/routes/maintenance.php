<?php

//offline page
\Legacy\System\Classes\Route::add('/offline', function () {

	//create view
	$view = new \Legacy\System\Classes\View('errors/offline');

	//setup view
	$view->title = 'Offline';
	$view->template = 'void';
	$view->middlewares = ['not_public'];

	//render view
	$view->show();
});

//offline page
\Legacy\System\Classes\Route::add('/wip', function () {

	//create view
	$view = new \Legacy\System\Classes\View('errors/wip');

	//setup view
	$view->title = 'Offline';
	$view->template = 'void';

	//render view
	$view->show();
});
