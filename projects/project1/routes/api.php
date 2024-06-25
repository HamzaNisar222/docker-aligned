<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Api\AuthController;

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
