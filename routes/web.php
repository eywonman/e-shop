<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\GuitarController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::middleware(['auth', 'verified'])->get('/guitars', [GuitarController::class, 'index'])->name('guitars.index');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});


Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');


Route::fallback(function () {
    return 'This Page is Not Found, Please Try Again';
});