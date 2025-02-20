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
Route::view('/pages/bufferlogs', 'pages.bufferlogs');
Route::view('/pages/Selling', 'pages.Selling');
Route::view('/pages/sellinglogs', 'pages.sellinglogs');
Route::view('/pages/buffer', 'pages.buffer');
Route::view('/pages/audit', 'pages.audit');


Route::get('/pages/product_lists','ProductsList@getproducts')->name('product_lists');
Route::get('/product_display','ProductsList@displayproductlist')->name('product_lists.display');
Route::post('/product_lists','ProductsList@addproducts')->name('products.store');
Route::get('/pages/product_lists{id}','ProductsList@deleteproduct')->name('products.delete');
Route::post('/pages/product_lists/edit','ProductsList@editproduct')->name('products.edit');
Route::get('/pages/product_lists/editform', 'ProductsList@editform')->name('products.editform');


Route::get('/pages/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
Route::post('pages/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');

Route::get('/pages/receiving', [ReceivingController::class, 'index'])->name('receivings.index');
Route::post('pages/receivings', [ReceivingController::class, 'store'])->name('receivings.store');

