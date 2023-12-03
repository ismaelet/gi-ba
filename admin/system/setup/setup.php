<?php

// fetch app config
require_once(__DIR__ . '/../../app/config/config.php');
require_once(__DIR__ . '/../../app/config/vendor-config.php');

// define class autoloader
spl_autoload_register(function ($class) {
	$class = str_replace('\\', '/', $class);

	if (file_exists($file = SERVER_ROOT . '/app/classes/' . $class . '.php')) include_once($file);
	else if (file_exists($file = SERVER_ROOT . '/system/classes/' . $class . '.php')) include_once($file);
	else if (defined('PUBLIC_SERVER_ROOT') && file_exists($file = PUBLIC_SERVER_ROOT . '/app/classes/' . $class . '.php')) include_once($file);

	if (method_exists($class, '__constructStatic')) {
		$class::__constructStatic();
	}
});

// render
Setup::execute();

class Setup {
	// constants
	static private function initializeConstants() {
		global $sysConfig;

		define('SERVER_ROOT', explode('/system', str_replace("\\", "/", __DIR__))[0]);
		define('CLIENT_ROOT', str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '', SERVER_ROOT));
		define('CLIENT_URL', !empty($sysConfig['domain']) ? 'https://' . $sysConfig['domain'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . CLIENT_ROOT);
		define('ICONS_DIR', '/system/assets/icons/');
		define('APP_ICONS_DIR', '/app/assets/icons/');
		define('IMG_DIR', '/app/assets/images/');
		define('LOGO_DIR', '/app/assets/logo/');
	}

	// functions
	static private function initializeFunctions() {
		$modules = glob(SERVER_ROOT . '/system/functions/*.php', GLOB_BRACE);
		foreach ($modules as $module) include_once($module);

		$modules = glob(SERVER_ROOT . '/app/functions/*.php', GLOB_BRACE);
		foreach ($modules as $module) include_once($module);
	}

	// vendor
	static private function initializeVendor() {
		if (file_exists($autoloadFile = SERVER_ROOT . '/vendor/autoload.php')) {
			require_once $autoloadFile;
		}
	}

	// routes
	static private function initializeRoutes() {
		foreach (scan('/system/routes', 'php') as $route) include_once($route);
		foreach (scan('/app/routes', 'php') as $route) include_once($route);
	}

	// connect
	static private function connect() {
		global $con;

		if (!sys_config('static')) {
			try {
				$con = new mysqli(sys_config('hostname'), sys_config('username'), sys_config('password'), sys_config('database'));
				$con->query('SET NAMES utf8');
			} catch (Exception $e) {
				$error = $e->getMessage();

				error_log('Connection error: ' . $error . PHP_EOL . 'On page: ' . $_SERVER['REQUEST_URI']);

				if (DEBUG || in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
					echo $error;
				}
			}
		}
	}

	// migrate
	static private function migrate() {
		if (!sys_config('static') && sys_config('migrate')) {
			$tables = glob(SERVER_ROOT . '/system/migration/*.php');
			foreach ($tables as $table) include_once($table);

			$tables = glob(SERVER_ROOT . '/app/migration/*.php');
			foreach ($tables as $table) include_once($table);
		}
	}

	// login
	static private function sessionLogin() {
		global $user;

		if (!sys_config('static')) {
			if (!isset($_SESSION['userId']) && isset($_COOKIE['auth_token'])) {
				login_direct($_COOKIE['auth_token']);
			}

			$userId = $_SESSION['userId'] ?? 0;
			$user = new User($userId);
		}
	}

	// language
	static private function sessionLanguage() {
		$acceptedLanguages = sys_config('languages');
		$defaultLanguage = sys_config('default-language');
		$browserLanguage = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
		$sessionLanguage = $_SESSION['language'] ?? null;
		$requestedLanguage = $_GET['lang'] ?? null;

		// remove unused session languages
		if ($sessionLanguage && !isset($acceptedLanguages[$sessionLanguage])) {
			$sessionLanguage = null;
			unset($_SESSION['language']);
		}

		// set the requested language
		if ($requestedLanguage && isset($acceptedLanguages[$requestedLanguage])) {
			$sessionLanguage = $_SESSION['language'] = $_GET['lang'];
		}

		// set the session language
		if (!$sessionLanguage) {
			$sessionLanguage = $_SESSION['language'] = isset($acceptedLanguages[$browserLanguage]) ? $browserLanguage : $defaultLanguage;
		}
	}

	// theme
	static private function sessionTheme() {
		$_SESSION['theme-color'] = sys_config('theme-color') ?: ($_COOKIE['theme-color'] ?? 'light');
	}

	// execute the setup
	static public function execute() {

		// load resources
		self::initializeConstants();
		self::initializeFunctions();
		self::initializeVendor();

		// connect
		self::connect();
		self::migrate();
		self::initializeRoutes();

		// start session
		session_start();
		date_default_timezone_set(sys_config('timezone'));

		// setup the session
		self::sessionLogin();
		self::sessionLanguage();
		self::sessionTheme();

		// other setups
		foreach (['admin', 'ecommerce'] as $setupFile) {
			if (file_exists($file = __DIR__ . '/' . $setupFile . '.php')) require_once($file);
		}

		// render page
		Route::run();
	}
}
