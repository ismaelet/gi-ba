<?php

// login
\Legacy\System\Classes\Route::add('/login', function () {
	\Legacy\System\Classes\View::setPage('access/login')
		->setTitle('Login')
		->setMiddlewares(['not-admin'])
		->setTemplate('void')
		->show();
});

// logout
\Legacy\System\Classes\Route::add('/logout', function () {
	\Legacy\System\Classes\View::setPage('access/logout')
		->setTitle('Logout')
		->setMiddlewares(['admin'])
		->setTemplate('void')
		->show();
});
