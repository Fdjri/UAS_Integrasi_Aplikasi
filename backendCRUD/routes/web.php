<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminServiceProviderController;
use App\Http\Controllers\ServiceProvider\ServiceProviderController;
use App\Http\Controllers\ServiceProvider\ServiceProviderCustomerController;
use App\Http\Controllers\ServiceProvider\ServiceProviderProfileController;
use App\Http\Controllers\ServiceProvider\ServiceProviderServiceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (login, logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register khusus service provider
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::middleware(['auth'])->group(function () {
    // Admin routes dengan prefix dan middleware role
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Management Customer
        Route::get('/customers', [AdminCustomerController::class, 'indexCustomers'])->name('admin.customers.index');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'showCustomer'])->name('admin.customers.show');

        // Management Booking
        Route::get('/bookings', [AdminCustomerController::class, 'indexBookings'])->name('admin.bookings.index');
        Route::get('/bookings/{id}', [AdminCustomerController::class, 'showBooking'])->name('admin.bookings.show');
        Route::put('/bookings/{id}', [AdminCustomerController::class, 'bookingsUpdate'])->name('admin.bookings.update');

        // Management Payment
        Route::get('/payments', [AdminCustomerController::class, 'indexPayments'])->name('admin.payments.index');
        Route::put('/payments/{id}', [AdminCustomerController::class, 'updatePayment'])->name('admin.payments.update');

        // Management Service Providers
        Route::get('/service-providers', [AdminServiceProviderController::class, 'index'])->name('admin.service_providers.index');
        Route::get('/service-providers/{id}', [AdminServiceProviderController::class, 'show'])->name('admin.service_providers.show');
    });

    // Service provider routes dengan prefix dan middleware role
    Route::prefix('provider')->middleware('role:service_provider')->group(function () {
        // Dashboard provider
        Route::get('/dashboard', [ServiceProviderController::class, 'index'])->name('provider.dashboard');

        // Management Booking untuk provider menggunakan ServiceProviderCustomerController
        Route::get('/bookings', [ServiceProviderCustomerController::class, 'index'])->name('provider.bookings.index');
        Route::put('/bookings/{id}', [ServiceProviderCustomerController::class, 'update'])->name('provider.bookings.update');

        // Management Payment untuk provider (gunakan ServiceProviderController atau buat controller lain)
        Route::get('/payments', [ServiceProviderCustomerController::class, 'paymentsIndex'])->name('provider.payments.index');
        Route::put('/payments/{id}', [ServiceProviderCustomerController::class, 'paymentsUpdate'])->name('provider.payments.update');


        // Management Service untuk provider (jika ada)
        Route::get('/services', [ServiceProviderServiceController::class, 'index'])->name('provider.services.index');
        Route::post('/services', [ServiceProviderServiceController::class, 'store'])->name('provider.services.store');
        Route::put('/services/{id}', [ServiceProviderServiceController::class, 'update'])->name('provider.services.update');
        Route::delete('/services/{id}', [ServiceProviderServiceController::class, 'destroy'])->name('provider.services.destroy');
        
        // Profile untuk provider
        Route::get('/profile', [ServiceProviderProfileController::class, 'index'])->name('provider.profile.index');
        Route::put('/profile/update', [ServiceProviderProfileController::class, 'update'])->name('provider.profile.update');
    });
});
