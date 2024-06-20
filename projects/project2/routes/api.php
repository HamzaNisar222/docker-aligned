<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\ServiceController;
// use App\Http\Controllers\Api\SubServicesController;
// use App\Http\Controllers\Api\AdminServiceController;
// use App\Http\Controllers\api\VendorServiceController;
// use App\Http\Controllers\Api\ServiceRegistrationController;

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

require __DIR__. '/public/public.php';
require __DIR__. '/auth/auth.php';
require __DIR__. '/admin/admin.php';
require __DIR__. '/vendor/vendor.php';
