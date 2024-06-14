<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VendorServiceController;
use App\Http\Controllers\api\ServiceRegistrationController;


Route::middleware(['auth.token', 'role:user'])->group(function () {
    Route::post('/service-registrations', [ServiceRegistrationController::class, 'create']);

    Route::get('/service-registrations/pending', [VendorServiceController::class, 'pending']);
    Route::get('/service-registrations/approved', [VendorServiceController::class, 'approved']);
});
