<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\ContactRequestController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/**
 * Home
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Gallery
 */
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/collection/{collection}', [GalleryController::class, 'show'])->name('collection');

/**
 * Support routes
 */
Route::post('/contact-request/send', [ContactRequestController::class, 'send'])->name('contact-request.send');

/**
 * Admin routes
 */
Route::name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/orders/{order}/quote', [InvoiceController::class, 'downloadQuote'])->name('orders.quote.download');

    Route::get('/orders/{order}/delivery-note', [InvoiceController::class, 'downloadDeliveryNote'])->name('orders.delivery-note.download');
});
