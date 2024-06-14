<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubServicesController;
use App\Http\Controllers\Api\AdminServiceController;
use App\Http\Controllers\api\VendorServiceController;
use App\Http\Controllers\Api\ServiceRegistrationController;

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

// Authentication routes
Route::post('/register', [AuthController::class, 'register'])->middleware('validation:register');
Route::get('/register/confirm/{token}', [AuthController::class, 'confirmEmail'])->name('register.confirm');
Route::post('/login', [AuthController::class, 'login'])->middleware('validation:login');
Route::post('/logout', [AuthController::class, 'logout']);

// Public routes for services
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/sub-services/{serviceId}', [SubServicesController::class, 'index']);

// Admin routes (requires 'admin' role)
Route::middleware(['auth.token', 'role:admin'])->prefix('admin')->group(function () {
    // Main Service routes
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    // Sub Service routes
    Route::post('/sub-services/{serviceId}', [SubServicesController::class, 'store']);
    Route::put('/sub-services/{id}', [SubServicesController::class, 'update']);
    Route::delete('/sub-services/{id}', [SubServicesController::class, 'destroy']);

    // Approve a service registration
    Route::post('/service-registrations/approve/{id}', [ServiceRegistrationController::class, 'approve']);

    // Reject a service registration
    Route::post('/service-registrations/reject/{id}', [ServiceRegistrationController::class, 'reject']);

     // Routes for service registrations
     Route::get('/service-registrations/pending', [AdminServiceController::class, 'pending']);
     Route::get('/service-registrations/approved', [AdminServiceController::class, 'approved']);
     Route::get('/service-registrations/rejected', [AdminServiceController::class, 'rejected']);

    // Routes for service registrations of a specific user
    Route::get('/users/{userId}/service-registrations/pending', [AdminServiceController::class, 'userPending']);
    Route::get('/users/{userId}/service-registrations/approved', [AdminServiceController::class, 'userApproved']);
});

// Vendor routes (requires 'user' role)
Route::middleware(['auth.token', 'role:user'])->group(function () {
    Route::post('/service-registrations', [ServiceRegistrationController::class, 'create']);

    Route::get('/service-registrations/pending', [VendorServiceController::class, 'pending']);
    Route::get('/service-registrations/approved', [VendorServiceController::class, 'approved']);
});
