<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubServicesController;
use App\Http\Controllers\Api\AdminServiceController;
use App\Http\Controllers\Api\ServiceRegistrationController;

Route::middleware(['auth.token', 'role:admin'])->prefix('admin')->group(function () {
    // Admins have all permissions

    // Manage subadmins
    Route::post('/create-subadmin', [AdminController::class, 'createSubAdmin'])->middleware('validation:subadmin');
    Route::delete('/delete-subadmin/{id}', [AdminController::class, 'deleteSubAdmin']);
    Route::post('/activate-subadmin/{id}', [AdminController::class, 'activateSubAdmin']);
    Route::post('/deactivate-subadmin/{id}', [AdminController::class, 'deactivateSubAdmin']);
    Route::post('/assign-permissions/{id}', [AdminController::class, 'assignPermissions']);

    // Manage users
    Route::post('/create-user', [AdminController::class, 'createUser'])->middleware('validation:register');
    Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/activate-user/{id}', [AdminController::class, 'activateUser']);
    Route::post('/deactivate-user/{id}', [AdminController::class, 'deactivateUser']);

    // Main Service routes
    Route::post('/services', [ServiceController::class, 'store']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

    // Sub Service routes
    Route::post('/sub-services/{serviceId}', [SubServicesController::class, 'store']);
    Route::put('/sub-services/{id}', [SubServicesController::class, 'update']);
    Route::delete('/sub-services/{id}', [SubServicesController::class, 'destroy']);

    // Approve/reject service registrations
    Route::post('/service-registrations/approve/{id}', [ServiceRegistrationController::class, 'approve']);
    Route::post('/service-registrations/reject/{id}', [ServiceRegistrationController::class, 'reject']);

    // Routes for service registrations
    Route::get('/service-registrations/pending', [AdminServiceController::class, 'pending']);
    Route::get('/service-registrations/approved', [AdminServiceController::class, 'approved']);
    Route::get('/service-registrations/rejected', [AdminServiceController::class, 'rejected']);

    // Routes for service registrations of a specific user
    Route::get('/users/{userId}/service-registrations/pending', [AdminServiceController::class, 'userPending']);
    Route::get('/users/{userId}/service-registrations/approved', [AdminServiceController::class, 'userApproved']);
});

Route::middleware(['auth.token', 'role:subadmin'])->prefix('subadmin')->group(function () {
    // Subadmins have restricted permissions


    // Manage users (subset of user management)
    Route::middleware('check.permissions:manage_users')->group(function () {
        Route::post('/create-user', [AdminController::class, 'createUser']);
        Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser']);
        Route::post('/activate-user/{id}', [AdminController::class, 'activateUser']);
        Route::post('/deactivate-user/{id}', [AdminController::class, 'deactivateUser']);
    });

    // // Sub Service routes (subset of service management)
    Route::middleware('check.permissions:manage_services')->group(function () {


        // Main Service routes
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{id}', [ServiceController::class, 'update']);
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
        // Sub services routes
        Route::post('/sub-services/{serviceId}', [SubServicesController::class, 'store']);
        Route::put('/sub-services/{id}', [SubServicesController::class, 'update']);
        Route::delete('/sub-services/{id}', [SubServicesController::class, 'destroy']);
    });
});
