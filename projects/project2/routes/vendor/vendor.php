<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VendorServiceController;
use App\Http\Controllers\Api\VendorApprovalController;
use App\Http\Controllers\api\ServiceRegistrationController;


Route::middleware(['auth.token', 'role:user'])->group(function () {
    Route::post('/service-registrations', [ServiceRegistrationController::class, 'create']);
    Route::get('/service-registrations/pending', [VendorServiceController::class, 'pending']);
    Route::get('/service-registrations/approved', [VendorServiceController::class, 'approved']);

    //OFFERS crud
    Route::post('/service-offer', [VendorServiceController::class, 'addServiceOffer'])->middleware('validation:serviceoffer');
    Route::put('/service-offers/{id}', [VendorServiceController::class, 'updateServiceOffer']);
    Route::delete('/service-offer/{id}', [VendorServiceController::class, 'deleteServiceOffer']);
    // get Vendor services
    Route::get('/vendor-offerings', [VendorServiceController::class, 'getVendorOfferings']);

    // Client requests
    Route::get('/vendor/clients', [VendorApprovalController::class, 'getClients']);
    Route::get('/vendor/requests', [VendorApprovalController::class, 'getAllRequests']);
    Route::get('/vendor/requests/pending', [VendorApprovalController::class, 'getPendingRequests']);
    Route::get('/vendor/requests/approved', [VendorApprovalController::class, 'getApprovedRequests']);
    Route::get('/vendor/requests/rejected', [VendorApprovalController::class, 'getRejectedRequests']);
    Route::put('/vendor/requests/{id}/approve', [VendorApprovalController::class, 'approveRequest']);
    Route::put('/vendor/requests/{id}/disapprove', [VendorApprovalController::class, 'disapproveRequest']);



});

Route::get('/available-services', [VendorServiceController::class, 'getAvailableServices']);
Route::get('/vendor-offerings/{vendorId}', [VendorServiceController::class, 'getVendorSpecificOfferings']);

