<?php

use App\Http\Controllers\LegacyController;
use Illuminate\Support\Facades\Route;

/**
 * * Legacy routes
 */
Route::any('{path}', LegacyController::class)->where('path', '.*');
