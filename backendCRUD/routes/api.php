<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerServiceController;

// Route Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register/customer', [AuthController::class, 'registerCustomer']);

// Route publik tanpa autentikasi, untuk landing page sebelum login
Route::get('services/public', [CustomerServiceController::class, 'publicServices']);

// Route dengan middleware autentikasi Sanctum, untuk user yang sudah login
Route::middleware('auth:sanctum')->group(function () {
    Route::get('services', [CustomerServiceController::class, 'index']);
    Route::get('services/{serviceId}', [CustomerServiceController::class, 'show']);
});
