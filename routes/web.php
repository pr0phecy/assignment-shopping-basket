<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
});

Route::prefix('basket')->name('basket.')->group(function () {
    Route::get('/', [BasketController::class, 'index'])->name('index');
    Route::post('/add/{product}', [BasketController::class, 'add'])->name('add');
    Route::post('/remove/{product}', [BasketController::class, 'remove'])->name('remove');
    Route::post('/clear', [BasketController::class, 'clear'])->name('clear');
});

Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/complete', [CheckoutController::class, 'complete'])->name('complete');
});
