<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class ServiceProviderCustomerController extends Controller
{
    public function __construct()
    {
        // Middleware agar hanya service_provider yang bisa akses
        $this->middleware(['auth', 'role:service_provider']);
    }

    /**
     * Display a listing of bookings owned by logged-in service provider.
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil bookings milik service provider ini dengan relasi customer dan service
        $bookings = Booking::with(['customer.customerProfile', 'service'])
            ->whereHas('service', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('provider.bookings.index', compact('bookings'));
    }

    /**
     * Update status booking milik service provider.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,failed',
        ]);

        $userId = Auth::id();

        $booking = Booking::where('id', $id)
            ->whereHas('service', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->firstOrFail();

        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('provider.bookings.index')
                         ->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Display a listing of payments owned by logged-in service provider.
     */
    public function paymentsIndex()
    {
        $userId = Auth::id();

        // Ambil payments yang terkait booking milik service provider ini
        $payments = Payment::with(['booking.customer.customerProfile', 'booking.service'])
            ->whereHas('booking.service', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('provider.payments.index', compact('payments'));
    }

    /**
     * Update status payment milik service provider.
     */
    public function paymentsUpdate(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,confirmed,cancelled,completed,failed,refunded,paid',
        ]);

        $userId = Auth::id();

        $payment = Payment::where('payment_id', $id)
            ->whereHas('booking.service', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->firstOrFail();

        $payment->payment_status = $request->payment_status;
        $payment->save();

        return redirect()->route('provider.payments.index')
                         ->with('success', 'Status payment berhasil diperbarui.');
    }
}
