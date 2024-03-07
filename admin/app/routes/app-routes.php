<?php

// home
Route::add('/', function () {
	View::setPage('home')
		->setTemplate('admin')
		->addMiddlewares(['admin'])
		->show();
});
