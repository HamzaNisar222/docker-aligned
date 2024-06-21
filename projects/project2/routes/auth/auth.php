<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// User Login
Route::post('/register', [AuthController::class, 'register'])->middleware('validation:register');
Route::get('/register/confirm/{token}', [AuthController::class, 'confirmEmail'])->name('register.confirm');
Route::post('/login', [AuthController::class, 'login'])->middleware('validation:login');
Route::post('/logout', [AuthController::class, 'logout']);


//ADMIN LOGIN
Route::prefix('admin')->group(function () {

    Route::post('/login',[AuthController::class,'adminLogin'])->middleware('validation:login')->name('admin.login');

});
