<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:service_provider']);
    }

    /**
     * Display dashboard with charts data.
     */
    public function index()
    {
        $userId = auth()->id();

        // Semua status booking yang ingin ditampilkan
        $allBookingStatuses = ['pending', 'confirmed', 'cancelled', 'completed', 'failed'];

        $bookingsStatusRaw = Booking::whereHas('service', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

        $bookingsStatus = [];
        foreach ($allBookingStatuses as $status) {
            $bookingsStatus[$status] = $bookingsStatusRaw[$status] ?? 0;
        }

        // Semua status payment yang ingin ditampilkan
        $allPaymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        $paymentsStatusRaw = Payment::whereHas('booking.service', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->select('payment_status', DB::raw('count(*) as total'))
        ->groupBy('payment_status')
        ->pluck('total', 'payment_status')
        ->toArray();

        $paymentsStatus = [];
        foreach ($allPaymentStatuses as $status) {
            $paymentsStatus[$status] = $paymentsStatusRaw[$status] ?? 0;
        }

        // Total booking per bulan
        $monthlyBookingTotalsRaw = Booking::whereHas('service', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->selectRaw('MONTH(booking_date) as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Total payment per bulan
        $monthlyPaymentTotalsRaw = Payment::whereHas('booking.service', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->selectRaw('MONTH(payment_date) as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        $monthlyBookingTotals = [];
        $monthlyPaymentTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyBookingTotals[$m] = $monthlyBookingTotalsRaw[$m] ?? 0;
            $monthlyPaymentTotals[$m] = $monthlyPaymentTotalsRaw[$m] ?? 0;
        }

        return view('provider.dashboard', compact(
            'bookingsStatus',
            'paymentsStatus',
            'monthlyBookingTotals',
            'monthlyPaymentTotals'
        ));
    }

    public function create()
    {
        // Optional: Show form to create new resource
    }

    public function store(Request $request)
    {
        // Optional: Handle storing new resource
    }

    public function show(string $id)
    {
        // Optional: Show specific resource
    }

    public function edit(string $id)
    {
        // Optional: Show form to edit resource
    }

    public function update(Request $request, string $id)
    {
        // Optional: Handle update resource
    }

    public function destroy(string $id)
    {
        // Optional: Handle delete resource
    }
}
