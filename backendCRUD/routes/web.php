<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminServiceProviderController;
use App\Http\Controllers\ServiceProvider\ServiceProviderController;

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

        // Routes untuk management customer, booking, payment
        Route::get('/customers', [AdminCustomerController::class, 'indexCustomers'])->name('admin.customers.index');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'showCustomer'])->name('admin.customers.show');

        Route::get('/bookings', [AdminCustomerController::class, 'indexBookings'])->name('admin.bookings.index');
        Route::get('/bookings/{id}', [AdminCustomerController::class, 'showBooking'])->name('admin.bookings.show');
        Route::put('/bookings/{id}', [AdminCustomerController::class, 'bookingsUpdate'])->name('admin.bookings.update');

        Route::get('/payments', [AdminCustomerController::class, 'indexPayments'])->name('admin.payments.index');
        Route::put('/payments/{id}', [AdminCustomerController::class, 'updatePayment'])->name('admin.payments.update');

        // Route Management Service Providers
        Route::get('/service-providers', [AdminServiceProviderController::class, 'index'])->name('admin.service_providers.index');
        Route::get('/service-providers/{id}', [AdminServiceProviderController::class, 'show'])->name('admin.service_providers.show');
    });

    // Service provider routes dengan prefix dan middleware role
    Route::prefix('provider')->middleware('role:service_provider')->group(function () {
        // Dashboard provider
        Route::get('/dashboard', [ServiceProviderController::class, 'index'])->name('provider.dashboard');

        // Contoh route management booking dan payment untuk provider (pastikan controller dan method ada)
        Route::get('/bookings', [ServiceProviderController::class, 'bookingsIndex'])->name('provider.bookings.index');
        Route::get('/payments', [ServiceProviderController::class, 'paymentsIndex'])->name('provider.payments.index');

        // Tambahkan route lain khusus service provider sesuai kebutuhan
    });
});
