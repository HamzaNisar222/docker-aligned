<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ServiceController;
use App\Http\Controllers\api\SubServicesController;


Route::get('/services', [ServiceController::class, 'index']);
Route::get('/sub-services/{serviceId}', [SubServicesController::class, 'index']);
