<?php

// home
\Legacy\System\Classes\Route::add('/', function () {
	\Legacy\System\Classes\View::setPage('home')
		->setTemplate('admin')
		->addMiddlewares(['admin'])
		->show();
});
