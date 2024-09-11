<?php

use App\Helpers\ArrayHelper;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;

/**
 * Debug an array
 */
function dd_array(array $array): void {
	dd('<pre>' . print_r($array, true) . '</pre>');
}

/**
 * Check if the user is an admin
 */
function is_admin(): bool {
	return Auth::check() && Auth::user()->is_admin;
}

/**
 * Check if the user is a supervisor
 */
function is_superadmin(): bool {
	return Auth::check() && Auth::user()->is_superadmin;
}

/**
 * Get the path of a file in the public storage directory
 */
function public_asset(string $path): string {
	return FileHelper::publicAsset($path);
}

/**
 * Get the path of a file in the public storage directory
 */
function public_storage(?string $path = null): string {
	return FileHelper::publicStoragePath($path);
}

/**
 * Import an svg and embed it in the HTML
 */
function embed_svg(string $fileName, array $customClasses = []): ?string {
	return FileHelper::embedSvg($fileName, $customClasses);
}

/**
 * Import an icon and embed it in the HTML
 */
function embed_icon(string $fileName, array $customClasses = []): ?string {
	return FileHelper::embedSvg($fileName, $customClasses, true);
}

/**
 * Explode a string by the last occurrence of a separator
 */
function explode_last($separator, $string): array {
	return ArrayHelper::explodeLast($separator, $string);
}
