<?php

//setup global variables
global $user;

//check if to use the lighter version for ajax
$full = $full ?? true;

//set migration status
$migration = false;

//retrive and start session
session_start();

//set constants
define('SERVER_ROOT', explode('/system', str_replace("\\", "/", __DIR__))[0]);
define('__HROOT__', str_replace($_SERVER['DOCUMENT_ROOT'], '', SERVER_ROOT));
define('__ICON__', '/system/resources/icons/');
define('__IMG__', '/app/resources/images/');
define('LOGO_DIR', '/app/resources/logo/');

include_once(SERVER_ROOT . '/app/config/config.php');

date_default_timezone_set($sysData['timezone']);

//connect
if (!$sysDB['static']) {
	$con = new mysqli($sysDB['host'], $sysDB['user'], $sysDB['pass'], $sysDB['db']) or false;
	$con->query('SET NAMES utf8');
}

//load basic resources
include_once('migration.php');
include_once('classes.php');
include_once('modules.php');
include_once('functions.php');

if ($full) {
	include_once('middlewares.php');
	foreach (scan('/app/routes', 'php') as $route) include_once($route);
}

//recover login
if (!isset($_SESSION['userID']) && isset($_COOKIE['auth_token'])) login_direct($_COOKIE['auth_token']);

$userID = $_SESSION['userID'] ?? 0;

$user = new User($userID);

//view content
if ($full) Route::run();
