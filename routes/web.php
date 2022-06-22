<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Vender\AuthController as VendorAuthController;
use App\Http\Controllers\Buyer\AuthController as BuyerAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Vender\OrderController as VendorOrderController;
use App\Http\Controllers\Vender\ProductController;
use App\Enums\UserType;
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


// guest middleware
Route::group(['middleware' => 'guest'], function () {
      Route::get('/vendor/login', [VendorAuthController::class, 'getLogin'])->name('vendor.login');
      Route::get('/buyer/login', [BuyerAuthController::class, 'getLogin'])->name('buyer.login');
      Route::get('/user/register', [RegisterController::class, 'getRegister'])->name('user.register');
      Route::get('/login-as', [HomeController::class, 'getLoginAs'])->name('user.login-as');
});

// auth middleware Vendor
Route::group(['middleware' => ['auth:sanctum', 'check.user:' . UserType::VENDOR]], function () {
      Route::get('/vendor/dashboard', [VendorAuthController::class, 'dashboard'])->name('vendor.dashboard');
      Route::get('/vendor/orders', [VendorOrderController::class, 'index'])->name('vendor.orders.index');
});


// auth middleware Buyer
Route::group(['middleware' => ['auth:sanctum', 'check.user:' . UserType::BUYER]], function () {
      Route::get('/', [HomeController::class, 'index'])->name('home');
});
