<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Booking; 
use App\Models\Payment; 

class AdminCustomerController extends Controller
{
    public function __construct()
    {
        // Middleware hanya admin yang bisa akses
        $this->middleware(['auth', 'role:admin']);
    }

    // Management Customer: list semua customer
    public function indexCustomers()
    {
        $customers = User::where('role', 'customer')
                        ->with('customerProfile')
                        ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    // Detail Customer
    public function showCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    // Management Booking: list booking customer dengan status dan relasi customer
    public function indexBookings(Request $request)
    {
        // Query booking dengan eager loading customer dan service
        $query = Booking::with(['customer', 'service']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    // Detail booking (optional, bisa ditambah jika perlu)
    public function showBooking($id)
    {
        $booking = Booking::with('customer', 'service')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function bookingsUpdate(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,failed',
        ]);

        // Cari booking berdasarkan id
        $booking = Booking::findOrFail($id);

        // Update status
        $booking->status = $request->status;
        $booking->save();

        // Redirect kembali ke halaman bookings dengan pesan sukses
        return redirect()->route('admin.bookings.index')
                        ->with('success', 'Status booking berhasil diperbarui.');
    }

    // Management Payment: list semua payment dengan filter status
    public function indexPayments(Request $request)
    {
        $query = Payment::with('customer', 'booking');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    // Detail payment (optional)
    public function showPayment($id)
    {
        $payment = Payment::with('customer', 'booking')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }
}
