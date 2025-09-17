<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[ProductController::class, 'showProduct']);
Route::get('/products', [ProductController::class, 'showProduct'])->name('products.index');
Route::get('/product/{id}/detail', [ProductController::class, 'detail'])->name('product.detail');


