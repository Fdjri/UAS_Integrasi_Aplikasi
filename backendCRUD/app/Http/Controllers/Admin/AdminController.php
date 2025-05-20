<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Pastikan controller ini hanya bisa diakses oleh admin saja
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // Method untuk menampilkan dashboard admin
    public function dashboard()
    {
        // Hitung total user customer
        $totalCustomers = User::where('role', 'customer')->count();

        // Hitung total service provider
        $totalServiceProviders = User::where('role', 'service_provider')->count();

        // Ambil data status payment (pending, paid, failed, refunded)
        $paymentsStatus = DB::table('payments')
            ->select('payment_status', DB::raw('count(*) as total'))
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status')
            ->toArray();

        // Ambil data status booking (pending, confirmed, cancelled, completed, failed)
        $bookingsStatus = DB::table('bookings')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Kirim data ke view admin.dashboard
        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalServiceProviders',
            'paymentsStatus',
            'bookingsStatus'
        ));
    }
}
