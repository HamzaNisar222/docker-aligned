<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubServicesController;
use App\Http\Controllers\Api\AdminServiceController;
use App\Http\Controllers\Api\ServiceRegistrationController;

Route::middleware(['auth.token', 'role:subadmin'])->prefix('subadmin')->group(function () {
    // Subadmins have restricted permissions


    // Manage users (subset of user management)
    Route::middleware('check.permissions:manage_users')->group(function () {
        Route::post('/create-user', [AuthController::class, 'register'])->middleware('validation:register');
        Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);
        Route::post('/activate-user/{id}', [AdminController::class, 'activateUser']);
        Route::post('/deactivate-user/{id}', [AdminController::class, 'deactivateUser']);
    });

    // // Sub Service routes (subset of service management)
    Route::middleware('check.permissions:manage_services')->group(function () {


        // Main Service routes
        Route::post('/services', [ServiceController::class, 'store'])->middleware('validation:service');
        Route::put('/services/{id}', [ServiceController::class, 'update'])->middleware('validation:service');
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
        // Sub services routes
        Route::post('/sub-services/{serviceId}', [SubServicesController::class, 'store'])->middleware('validation:subservice');
        Route::put('/sub-services/{id}', [SubServicesController::class, 'update'])->middleware('validation:subservice');
        Route::delete('/sub-services/{id}', [SubServicesController::class, 'destroy']);
    });


    // Routes for Subadmin
    Route::middleware('check.permissions:manage_service_requests')->group(function () {


        // Approve/reject service registrations
        Route::post('/service-registrations/approve/{id}', [ServiceRegistrationController::class, 'approve']);
        Route::post('/service-registrations/reject/{id}', [ServiceRegistrationController::class, 'reject']);


        // Routes for service registrations
        Route::get('/service-registrations/pending', [AdminServiceController::class, 'pending']);
        Route::get('/service-registrations/approved', [AdminServiceController::class, 'approved']);
        Route::get('/service-registrations/rejected', [AdminServiceController::class, 'rejected']);

        // Routes for service registrations of a specific user
        Route::get('/rejected/{userId}', [AdminServiceController::class, 'userRejected']);
        Route::get('/approved/{userId}', [AdminServiceController::class, 'userApproved']);

    });


});
