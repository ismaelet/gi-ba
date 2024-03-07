<?php

//home
Route::add('/', function() {

	//create view
	$view = new View('home');

	//render view
	$view->show();

});

//gallery
Route::add('/gallery', function() {

	//create view
	$view = new View('gallery');

	//render view
	$view->show();

});

//collection
Route::add('/collection', function() {

	//create view
	$view = new View('collection');

	//render view
	$view->show();

});