<?php

// setup page
$tbl = 'users';
$index = 'id';
$add = false;
$rowLinks = 'edit?id={{id}}';

// search
$query['filter'] = "username LIKE CONCAT('%',?,'%') OR id=? OR email LIKE CONCAT('%',?,'%')";

// fields
$fields = [
	['title' => 'ID', 'name' => 'id', 'size' => 1],
	//['title' => 'Username', 'name' => 'username', 'size' => 3],
	['title' => 'Username / Email', 'name' => 'email', 'size' => 4],
	['title' => 'Nome', 'name' => 'firstname', 'size' => 3],
	['title' => 'Cognome', 'name' => 'lastname', 'size' => 3],
	//['title' => 'Email', 'name' => 'email', 'size' => 3],
	['title' => 'Gruppo', 'name' => 'groupId', 'type' => 'find', 'find' => 'name FROM user_groups WHERE id=?', 'size' => 2],
	['type' => 'btn'],
];

$buttons = [
	['title' => 'Modifica', 'color' => 'blu', 'url' => 'edit?id={{id}}'],
	['title' => 'Login', 'name' => 'login', 'color' => 'gre'],
];

// login as an user
if (isset($_POST['login'])) {
	$_SESSION['userId'] = $_POST['id'];
	redirect(__PUBLIC_CLIENT_URL__ . '/profile');
}
