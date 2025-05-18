<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCustomerController;

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
        Route::put('/admin/bookings/{id}', [AdminCustomerController::class, 'bookingsUpdate'])->name('admin.bookings.update');

        Route::get('/payments', [AdminCustomerController::class, 'indexPayments'])->name('admin.payments.index');
        Route::get('/payments/{id}', [AdminCustomerController::class, 'showPayment'])->name('admin.payments.show');
    });

    // Untuk service provider dashboard
    Route::get('/provider/dashboard', function () {
        return view('provider.dashboard');
    })->name('provider.dashboard')->middleware('role:service_provider');
});
