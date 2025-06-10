<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerServiceController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\CustomerProfileController;

// Route Auth
Route::post('/login/customer', [AuthController::class, 'loginCustomer']);
Route::post('register/customer', [AuthController::class, 'registerCustomer']);

// Route publik tanpa autentikasi, untuk landing page sebelum login
Route::get('services/public', [CustomerServiceController::class, 'publicServices']);

// Route dengan middleware autentikasi Sanctum, untuk user yang sudah login
Route::middleware('auth:sanctum')->group(function () {
    // Service customer
    Route::get('services', [CustomerServiceController::class, 'index']);
    Route::get('services/{serviceId}', [CustomerServiceController::class, 'show']);

    // Booking customer
    Route::get('customer/services', [CustomerBookingController::class, 'listServices']); // opsional

    // Booking awal (tanpa booking_date)
    Route::post('customer/bookings/init', [CustomerBookingController::class, 'createInitialBooking']);

    // Update booking_date saat customer masuk halaman payment
    Route::put('customer/bookings/{bookingId}/update-date', [CustomerBookingController::class, 'updateBookingDate']);

    // Booking detail
    Route::get('customer/bookings/{id}', [CustomerBookingController::class, 'showBooking']);
    Route::get('customer/bookings/history', [CustomerBookingController::class, 'history']);

    // Customer Profile
    Route::get('customer/profile', [CustomerProfileController::class, 'show']);
    Route::put('customer/profile', [CustomerProfileController::class, 'update']);

    // Update payment: method, transaction_id, paid_at, payment_expiry
    Route::put('customer/payments/{paymentId}/update', [CustomerBookingController::class, 'updatePayment']);
    Route::get('customer/payments/{paymentId}/success', [CustomerBookingController::class, 'getPaymentSuccess']);
});