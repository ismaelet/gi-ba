<?php

function sysDB($field) {

	global $sysDB;

	return $sysDB[$field] ?? false;
}

function sysData($field) {

	global $sysData;

	return $sysData[$field] ?? false;
}

function sysVar($field) {

	global $sysVar;

	return $sysVar[$field] ?? false;
}

function sysConfig($field) {
	global $sysDB;

	return $sysDB[$field] ?? false;
}
