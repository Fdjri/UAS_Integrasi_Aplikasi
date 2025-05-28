<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerServiceController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\CustomerPaymentController;
use App\Http\Controllers\Customer\CustomerProfileController;

// Route Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register/customer', [AuthController::class, 'registerCustomer']);

// Route publik tanpa autentikasi, untuk landing page sebelum login
Route::get('services/public', [CustomerServiceController::class, 'publicServices']);

// Route dengan middleware autentikasi Sanctum, untuk user yang sudah login
Route::middleware('auth:sanctum')->group(function () {
    // Service customer
    Route::get('services', [CustomerServiceController::class, 'index']);
    Route::get('services/{serviceId}', [CustomerServiceController::class, 'show']);

    // Booking customer
    Route::get('customer/services', [CustomerBookingController::class, 'listServices']); // opsional, list layanan khusus customer
    Route::post('customer/bookings', [CustomerBookingController::class, 'createBooking']);
    Route::get('customer/bookings/{id}', [CustomerBookingController::class, 'showBooking']);

    // Customer Profile
    Route::get('customer/profile', [CustomerProfileController::class, 'show']);
    Route::put('customer/profile', [CustomerProfileController::class, 'update']);
});

// Webhook Midtrans untuk notifikasi pembayaran (tidak perlu autentikasi)
Route::post('payment/webhook', [CustomerPaymentController::class, 'handleWebhook']);
