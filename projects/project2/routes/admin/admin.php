<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ServiceController;
use App\Http\Controllers\api\SubServicesController;
use App\Http\Controllers\Api\AdminServiceController;
use App\Http\Controllers\api\ServiceRegistrationController;


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
