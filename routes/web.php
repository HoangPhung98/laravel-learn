<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', [PageController::class, 'getHomePage'])->name('homepage');
Route::get('/producttype/{type}', [PageController::class, 'getProductType'])->name('producttype');
Route::get('/product/{id}', [PageController::class, 'getProduct'])->name('product');
Route::get('/contact', [PageController::class, 'getContact'])->name('contact');
Route::get('/about', [PageController::class, 'getAbout'])->name('about');

Route::get('/add-to-cart/{id}', [PageController::class, 'addToCart'])->name('add-to-cart');
Route::get('/delete-item-from-cart/{id}', [PageController::class,'deleteItemFromCart'])->name('delete-item-from-cart');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::post('/chotdon', [PageController::class, 'chotDon'])->name('chotdon');

