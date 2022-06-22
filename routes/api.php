<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vender\AuthController as VendorAuthController;
use App\Http\Controllers\Buyer\AuthController as BuyerAuthController;
use App\Http\Controllers\Buyer\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Vender\ProductController;
use App\Enums\UserType;
use App\Http\Controllers\Vender\OrderController as VendorOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/user/register', [RegisterController::class, 'store']);
Route::post('/buyer/login', [BuyerAuthController::class, 'buyerLogin']);
Route::post('/vendor/login', [VendorAuthController::class, 'vendorLogin']);

Route::middleware('auth:sanctum')->group(function () {

    Route::group(['middleware' => ['check.user:' . UserType::VENDOR]], function () {
        Route::apiResource('/products', ProductController::class);
        Route::get('/products/{id}/edit', [ProductController::class, 'getProduct']);
        Route::get('/order/{id}/products', [VendorOrderController::class, 'orderDetails']);
    });

    Route::group(['middleware' => ['check.user:' . UserType::BUYER]], function () {
        Route::post('/add-to-cart', [UserController::class, 'addToCart']);
        Route::get('/no_of_cart_items', [UserController::class, 'noOfCartItems']);
        Route::post('/placeorder', [UserController::class, 'placeOrder']);
    });

    // user logout
    Route::post('/logout', function (Request $request) {
        auth()->user()->tokens()->delete();
        $request->session()->invalidate();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful',
        ], 200);
    });
});
