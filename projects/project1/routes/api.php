<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\ClientServiceController;
use App\Http\Controllers\api\StripePaymentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// User Login
Route::post('/register', [AuthController::class, 'register'])->middleware('validation:register');
Route::get('/register/confirm/{token}', [AuthController::class, 'confirmEmail'])->name('register.confirm');
Route::post('/login', [AuthController::class, 'login'])->middleware('validation:login');
Route::post('/logout', [AuthController::class, 'logout']);

// Route::middleware('auth.token')->group(function () {
//     Route::get('/vendor/offers', [VendorController::class, 'getVendorOffers']);
//     Route::post('/vendor/offers/{offerId}/request', [VendorController::class, 'requestOfferAvailability']);
// });
Route::get('/available-services', [VendorController::class, 'getAvailableServices']);
Route::get('/vendor-offerings/{vendorId}', [VendorController::class, 'getVendorSpecificOfferings']);


Route::middleware('auth.token')->group(function() {
    Route::post('/client/service', [ClientServiceController::class, 'store'])->middleware('validation:client')->name('clent.service');
    Route::get('client/service/pending', [ClientServiceController::class, 'pending']);
    Route::get('client/service/approved', [ClientServiceController::class, 'approved']);
    Route::post('/create/payment/intent', [StripePaymentController::class, 'createPaymentIntent']);
    Route::post('/handle/payment/success', [StripePaymentController::class, 'handlePaymentSuccess']);
});
