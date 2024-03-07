<?php

//offline page
Route::add('/offline', function() {

	//create view
	$view = new View('errors/offline');

	//setup view
	$view->title = 'Offline';
	$view->template = 'void';
	$view->middlewares = ['not_public'];

	//render view
	$view->show();

});

//offline page
Route::add('/wip', function() {

	//create view
	$view = new View('errors/wip');

	//setup view
	$view->title = 'Offline';
	$view->template = 'void';

	//render view
	$view->show();

});