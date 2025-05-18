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
        $query = Booking::with(['customer.customerProfile', 'service']);

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
        // Membuat query dengan eager loading relasi booking, customer, customerProfile, dan service
        $query = Payment::with(['booking.customer.customerProfile', 'booking.service']);

        // Filter status pembayaran jika ada di query string
        if ($request->has('status') && $request->status != '') {
            $query->where('payment_status', $request->status);
        }

        // Mengurutkan berdasarkan tanggal dibuat terbaru dan melakukan paginasi
        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Mengembalikan view dengan data payments
        return view('admin.payments.index', compact('payments'));
    }

    // Update payment status
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,confirmed,cancelled,completed,failed',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->payment_status = $request->payment_status;
        $payment->save();

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Status payment berhasil diperbarui.');
    }
}
