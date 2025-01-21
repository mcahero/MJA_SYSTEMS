<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsList;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\WarehouseController;


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

// Example Routes
Route::view('/', 'landing');
Route::match(['get', 'post'], '/dashboard', function(){
    return view('dashboard');
});
Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank');
Route::view('/pages/Warehouselogs', 'pages.Warehouselogs');
Route::view('/pages/Selling', 'pages.Selling');
Route::view('/pages/Display', 'pages.Display');


Route::get('/pages/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
Route::post('pages/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');

Route::get('/pages/receiving', [ReceivingController::class, 'index'])->name('receivings.index');
Route::post('pages/receivings', [ReceivingController::class, 'store'])->name('receivings.store');

Route::get('/pages/product_lists',[ProductsList::class, 'index']);
Route::post('/products', [ProductsList::class, 'store'])->name('products.store');
