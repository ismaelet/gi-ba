<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegacyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Legacy routes except home
Route::any('{path}', LegacyController::class)->where('path', '.*');
