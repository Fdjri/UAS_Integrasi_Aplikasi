<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerBookingController extends Controller
{
    /**
     * List layanan (services) agar customer bisa pilih
     */
    public function listServices()
    {
        $services = Service::select('service_id', 'title', 'description', 'price', 'photo')->get();

        return response()->json([
            'status' => 'success',
            'data' => $services,
        ]);
    }

    /**
     * Buat booking + buat payment di tabel payments + dapatkan token midtrans
     */
    public function createBooking(Request $request)
    {
        $customerId = Auth::id();

        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,service_id'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        DB::beginTransaction();

        try {
            // Buat booking
            $booking = Booking::create([
                'customer_id' => $customerId,
                'service_id' => $validated['service_id'],
                'booking_date' => $validated['booking_date'],
                'status' => 'pending',
            ]);

            // Ambil harga service
            $service = Service::findOrFail($validated['service_id']);

            // Buat payment terkait booking
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => now(),
                'amount' => $service->price,
                'payment_status' => 'pending',
            ]);

            // Generate transaksi Midtrans
            $midtrans = new MidtransService();

            $transaction = $midtrans->createTransaction(
                $payment->payment_id, // gunakan payment_id sebagai order_id unik
                $payment->amount,
                [
                    'first_name' => Auth::user()->username,
                    'email' => Auth::user()->email,
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking dan payment berhasil dibuat',
                'booking' => $booking,
                'payment' => $payment,
                'midtrans_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat booking dan payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get booking detail beserta payment
     */
    public function showBooking($id)
    {
        $customerId = Auth::id();

        $booking = Booking::with('service', 'payment')
            ->where('id', $id)
            ->where('customer_id', $customerId)
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $booking,
        ]);
    }
}
