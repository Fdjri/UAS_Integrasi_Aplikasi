<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CustomerProfileFrontendController;
use App\Http\Controllers\Customer\CustomerBookingController;

// Halaman landing page umum (guest & user login)
Route::get('/', [LandingPageController::class, 'landingPage'])->name('landing');

// Auth: Login & Logout
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

// Auth: Register
Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Route yang hanya bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/customer/landing', [LandingPageController::class, 'landingPage'])->name('customer.landingpage');

    Route::get('/customer/detail/{serviceId}', [LandingPageController::class, 'show'])->name('customer.detail');

    Route::get('/customer/profile', [CustomerProfileFrontendController::class, 'index'])->name('customer.profile.frontend');
    Route::put('/customer/profile', [CustomerProfileFrontendController::class, 'update'])->name('customer.profile.update.frontend');

    Route::get('/customer/bookings', [CustomerBookingController::class, 'index'])->name('customer.bookings.index');
    Route::get('/customer/bookings/history', [CustomerBookingController::class, 'history'])->name('customer.bookings.history');

    Route::get('/customer/bookings/show/{bookingId}', [CustomerBookingController::class, 'show'])->name('customer.bookings.show');
    Route::get('/customer/bookings/init', [CustomerBookingController::class, 'showInitForm'])->name('customer.bookings.init.form');
    Route::post('/customer/bookings/init', [CustomerBookingController::class, 'createInitialBooking'])->name('customer.bookings.init');

    Route::get('/customer/bookings/{bookingId}/payment', [CustomerBookingController::class, 'showPaymentForm'])->name('customer.bookings.showPayment');

    // Hapus route proses pembayaran Midtrans, ganti dengan proses manual
    Route::post('/customer/bookings/{bookingId}/payment', [CustomerBookingController::class, 'processPayment'])->name('customer.bookings.processPayment');
    Route::get('/customer/bookings/{bookingId}/process-payment', [CustomerBookingController::class, 'showProcessPaymentForm'])->name('customer.bookings.processPaymentForm');
    Route::put('customer/payments/{paymentId}/update', [CustomerBookingController::class, 'updatePayment'])->name('customer.payments.update');
    Route::get('customer/payments/{paymentId}/success', [CustomerBookingController::class, 'paymentSuccess'])->name('customer.paymentSuccess');
});
