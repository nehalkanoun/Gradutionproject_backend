<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;

use App\Http\Controllers\AuthController ;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/







Route::group(['middleware'=>['auth:sanctum']],function () {
Route::post('admins',[AdminController::class,'store'])->name('admins.store');
Route::get('admins',[AdminController::class,'index'])->name('admins.index');
Route::get('admins/{id}',[AdminController::class,'show'])->name('admins.show');
Route::put('admins/{id}',[AdminController::class,'update'])->name('admins.update');
Route::delete('admins/{id}',[AdminController::class,'destroy'])->name('admins.destroy');
Route::apiResource('/admins','AdminController')->except(['index','show','update','destroy']);
});
Route::post('/auth/loginadmin', [AdminController::class, 'loginadmin']);
Route::post('/auth/logoutadmin', [AdminController::class, 'logoutadmin']);





Route::get('carts',[CartController::class,'index'])->name('carts.index');
Route::get('carts/{id}',[CartController::class,'show'])->name('carts.show');
Route::put('carts/{id}',[CartController::class,'update'])->name('carts.update');
Route::delete('carts/{id}',[CartController::class,'destroy'])->name('carts.destroy');
Route::post('carts',[CartController::class,'store'])->name('carts.store');

Route::group(['middleware'=>['auth:sanctum']],function () {
Route::get('customers',[CustomerController::class,'index'])->name('customers.index');
Route::get('customers/{id}',[CustomerController::class,'show'])->name('customers.show');
Route::put('customers/{id}',[CustomerController::class,'update'])->name('customers.update');
Route::delete('customers/{id}',[CustomerController::class,'destroy'])->name('customers.destroy');
// Route::post('customers',[CustomerController::class,'store'])->name('customers.store');
Route::apiResource('/customers','CustomerController')->except(['index','show','update','destroy']);
});
Route::post('/auth/registercustomer', [CustomerController::class, 'store']);
Route::post('/auth/logincustomer', [CustomerController::class, 'logincustomer']);
Route::post('/auth/logoutcustomer', [CustomerController::class, 'logoutcustomer']);





Route::get('orders',[OrderController::class,'index'])->name('orders.index');
Route::get('orders/{id}',[OrderController::class,'show'])->name('orders.show');
Route::put('orders/{id}',[OrderController::class,'update'])->name('orders.update');
Route::delete('orders/{id}',[OrderController::class,'destroy'])->name('orders.destroy');
Route::post('orders',[OrderController::class,'store'])->name('orders.store');
Route::get('/getsellerorders/{id}',[OrderController::class,'getsellerorders']);
Route::get('/getallorders',[OrderController::class,'getallorders']);
Route::put('adminupdateorder/{id}',[OrderController::class,'adminupdateorder']);


Route::get('products',[ProductController::class,'index'])->name('products.index');
Route::get('products/{id}',[ProductController::class,'show'])->name('products.show');
Route::put('products/{id}',[ProductController::class,'update'])->name('products.update');
Route::delete('products/{id}',[ProductController::class,'destroy'])->name('products.destroy');
Route::post('products',[ProductController::class,'store'])->name('products.store');
Route::get('/random-products', [ProductController::class, 'randomProducts']);
Route::get('/get-product/{id}', [ProductController::class, 'getproduct']);



Route::group(['middleware'=>['auth:sanctum']],function () {
Route::get('sellers',[SellerController::class,'index'])->name('sellers.index');
Route::get('sellers/{id}',[SellerController::class,'show'])->name('sellers.show');
Route::put('sellers/{id}',[SellerController::class,'update'])->name('sellers.update');
Route::delete('sellers/{id}',[SellerController::class,'destroy'])->name('sellers.destroy');

// Route::post('sellers',[SellerController::class,'store'])->name('sellers.store');
Route::apiResource('/sellers','SellerController')->except(['index','show','update','destroy']);
});

Route::post('/auth/registerseller', [SellerController::class, 'store']);
Route::post('/auth/loginseller', [SellerController::class, 'loginseller']);
Route::post('/auth/logoutseller', [SellerController::class, 'logoutseller']);




