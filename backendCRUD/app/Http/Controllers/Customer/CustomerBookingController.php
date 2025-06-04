<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomerBookingController extends Controller
{
    // List layanan (services)
    public function listServices()
    {
        $services = Service::select('service_id', 'title', 'description', 'price', 'photo')->get();

        return response()->json([
            'status' => 'success',
            'data' => $services,
        ]);
    }

    // Create initial booking
    public function createInitialBooking(Request $request)
    {
        $customerId = Auth::id();

        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,service_id'],
            'check_in' => ['nullable', 'date', 'after_or_equal:today'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'ticket_count' => ['nullable', 'integer', 'min:1'],
            'trip_type' => ['nullable', 'in:pergi,pulang-pergi'],
            'date_pergi' => ['nullable', 'date', 'after_or_equal:today'],
            'date_pulang' => ['nullable', 'date', 'after:date_pergi'],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $serviceType = $service->service_type;

        // Validasi tambahan berdasar tipe layanan
        if ($serviceType === 'hotel' && (empty($validated['check_in']) || empty($validated['check_out']))) {
            return response()->json(['status' => 'error', 'message' => 'Check-in dan check-out wajib untuk layanan hotel.'], 422);
        }
        if ($serviceType === 'event' && empty($validated['ticket_count'])) {
            return response()->json(['status' => 'error', 'message' => 'Jumlah tiket wajib diisi untuk layanan event.'], 422);
        }
        if ($serviceType === 'transportasi') {
            if (empty($validated['ticket_count']) || empty($validated['trip_type'])) {
                return response()->json(['status' => 'error', 'message' => 'Jumlah tiket dan tipe perjalanan wajib diisi untuk layanan transportasi.'], 422);
            }
            if (empty($validated['date_pergi'])) {
                return response()->json(['status' => 'error', 'message' => 'Tanggal pergi wajib diisi untuk layanan transportasi.'], 422);
            }
            if ($validated['trip_type'] === 'pulang-pergi' && empty($validated['date_pulang'])) {
                return response()->json(['status' => 'error', 'message' => 'Tanggal pulang wajib diisi jika tipe perjalanan pulang-pergi.'], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Hitung total price
            $totalPrice = 0;
            if ($serviceType === 'hotel') {
                $checkIn = Carbon::parse($validated['check_in']);
                $checkOut = Carbon::parse($validated['check_out']);
                $days = $checkOut->diffInDays($checkIn);
                if ($days < 1) $days = 1;
                $totalPrice = $service->price * $days;
            } elseif ($serviceType === 'event') {
                $ticketCount = $validated['ticket_count'] ?? 1;
                $totalPrice = $service->price * $ticketCount;
            } elseif ($serviceType === 'transportasi') {
                $ticketCount = $validated['ticket_count'] ?? 1;
                $multiplier = ($validated['trip_type'] === 'pulang-pergi') ? 2 : 1;
                $totalPrice = $service->price * $ticketCount * $multiplier;
            }

            // Simpan booking
            $booking = Booking::create([
                'customer_id' => $customerId,
                'service_id' => $validated['service_id'],
                'check_in' => $validated['check_in'] ?? null,
                'check_out' => $validated['check_out'] ?? null,
                'ticket_count' => $validated['ticket_count'] ?? null,
                'trip_type' => $validated['trip_type'] ?? null,
                'booking_date' => now(),
                'date_pergi' => $validated['date_pergi'] ?? null,
                'date_pulang' => $validated['date_pulang'] ?? null,
                'status' => 'pending',
            ]);

            // Buat payment manual dengan status pending
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => now(),
                'amount' => $totalPrice,
                'payment_status' => 'pending', // pembayaran manual, belum dibayar
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil dibuat',
                'booking' => $booking,
                'payment' => $payment,
                'total_price' => $totalPrice,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal membuat booking: ' . $e->getMessage()], 500);
        }
    }

    // Update booking_date saat buka halaman payment (opsional)
    public function updateBookingDate(Request $request, $bookingId)
    {
        $customerId = Auth::id();
        $booking = Booking::where('id', $bookingId)->where('customer_id', $customerId)->first();

        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking tidak ditemukan'], 404);
        }

        $booking->booking_date = now();
        $booking->save();

        return response()->json(['status' => 'success', 'message' => 'Booking date berhasil diperbarui', 'booking' => $booking]);
    }

    // Update payment: method, transaction_id, paid (manual)
    public function updatePayment(Request $request, $paymentId)
    {
        $customerId = Auth::id();

        $validator = Validator::make($request->all(), [
            'method' => 'sometimes|required|string|max:255',
            'transaction_id' => 'sometimes|nullable|string|max:255',
            'paid' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $payment = Payment::where('payment_id', $paymentId)
            ->whereHas('booking', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            })->first();

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment tidak ditemukan atau bukan milik Anda'
            ], 404);
        }

        if ($request->has('method')) {
            $payment->method = $request->method;
            $payment->payment_expiry = Carbon::now()->addDay();
        }

        if ($request->has('transaction_id')) {
            $payment->transaction_id = $request->transaction_id;
        }

        if ($request->has('paid') && filter_var($request->paid, FILTER_VALIDATE_BOOLEAN)) {
            $payment->paid_at = Carbon::now();
            $payment->payment_status = 'paid';  // update status jadi paid

            // Update status booking jadi confirmed
            $booking = $payment->booking;
            if ($booking) {
                $booking->status = 'confirmed';
                $booking->save();
            }
        }

        $payment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment berhasil diperbarui',
            'payment' => $payment
        ]);
    }

    public function getPaymentSuccess($paymentId)
    {
        $customerId = Auth::id();

        $payment = Payment::with('booking.service')
            ->where('payment_id', $paymentId)
            ->where('payment_status', 'paid') // hanya yang status paid
            ->whereHas('booking', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            })
            ->first();

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment tidak ditemukan atau belum dibayar',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'payment' => $payment,
            'booking' => $payment->booking,
        ]);
    }

    public function history(Request $request)
    {
        $customerId = Auth::id();

        $query = Booking::with(['service', 'payment'])
            ->where('customer_id', $customerId);

        $statuses = $request->input('status', []);
        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $bookings,
        ]);
    }

    // Detail booking & payment
    public function showBooking($id)
    {
        $customerId = Auth::id();

        $booking = Booking::with('service', 'payment')->where('id', $id)->where('customer_id', $customerId)->first();

        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $booking]);
    }
}
