<?php

$request = request()->path();

if (strpos($request, 'legacy-admin') === 0) {
	require __DIR__ . '/../../legacy/legacy-admin/index.php';
} else {
	require __DIR__ . '/../../legacy/index.php';
}
