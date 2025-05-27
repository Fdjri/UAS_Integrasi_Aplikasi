<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\DetailController; // pastikan sudah buat controller ini

// Halaman landing page umum (bisa untuk guest dan user login)
Route::get('/', [LandingPageController::class, 'landingPage'])->name('landing');

// Auth: Login & Logout
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

// Auth: Register
Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Landing page khusus Customer (setelah login)
Route::get('/customer/landing', [LandingPageController::class, 'landingPage'])->name('customer.landingpage');

Route::view('/customer/detail', 'customer.detail')->name('customer.detail');

Route::view('/customer/booking', 'customer.booking')->name('customer.booking');

Route::view('/customer/payment', 'customer.payment')->name('customer.payment');

Route::view('/customer/success', 'customer.success')->name('customer.success');