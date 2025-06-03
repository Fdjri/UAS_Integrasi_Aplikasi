<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerBookingController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_base_url', 'http://localhost:8000') . '/api';
    }

    // Daftar booking aktif (pending, confirmed)
    public function index()
    {
        $token = session('token');

        $response = Http::withToken($token)
            ->get($this->apiBaseUrl . '/customer/bookings', [
                'status' => ['pending', 'confirmed']
            ]);

        if ($response->failed()) {
            return back()->withErrors('Gagal mengambil data booking aktif.');
        }

        $bookings = $response->json('data', []);

        return view('customer.bookings.index', compact('bookings'));
    }

    // Riwayat booking
    public function history()
    {
        $token = session('token');

        $response = Http::withToken($token)
            ->get($this->apiBaseUrl . '/customer/bookings/history', [
                'status' => ['confirmed', 'cancelled', 'completed', 'failed']
            ]);

        if ($response->failed()) {
            dd($response->status(), $response->body());
            return back()->withErrors('Gagal mengambil data riwayat booking.');
        }

        // Ambil data booking dari response
        $bookings = $response->json('data', []);

        // Ubah menjadi collection agar mudah digunakan di blade
        $bookings = collect($bookings);

        // Format tanggal booking_date agar mudah ditampilkan
        $bookings = $bookings->map(function($booking) {
            $booking['booking_date'] = isset($booking['booking_date']) ? \Carbon\Carbon::parse($booking['booking_date']) : null;
            return $booking;
        });

        return view('customer.bookings.history', compact('bookings'));
    }

    // Detail booking
    public function show($bookingId)
    {
        $token = session('token');

        $response = Http::withToken($token)
            ->get($this->apiBaseUrl . "/customer/bookings/{$bookingId}");

        if ($response->failed()) {
            return back()->withErrors('Gagal mengambil data detail booking.');
        }

        $booking = $response->json('data');

        return view('customer.bookings.show', compact('booking'));
    }

    // Form booking awal
    public function showInitForm(Request $request)
    {
        $serviceId = $request->query('service_id') ?? $request->query('serviceId');
        if (!$serviceId) {
            abort(400, 'Parameter service_id wajib diisi');
        }

        $token = session('token');

        $response = Http::withToken($token)
            ->get($this->apiBaseUrl . "/services/{$serviceId}");

        if ($response->failed()) {
            abort(404, 'Service tidak ditemukan');
        }

        $data = $response->json();

        if (!isset($data['service'])) {
            abort(404, 'Service tidak ditemukan');
        }

        $service = $data['service'];
        $serviceType = $service['service_type'] ?? null;

        return view('customer.bookings.init', compact('service', 'serviceType'));
    }

    // Simpan booking awal
    public function createInitialBooking(Request $request)
    {
        $token = session('token');

        // Ambil service_type dari API services untuk validasi
        $serviceId = $request->input('service_id');
        $serviceResp = Http::withToken($token)->get($this->apiBaseUrl . "/services/{$serviceId}");
        if ($serviceResp->failed()) {
            return back()->withErrors('Service tidak ditemukan.');
        }
        $service = $serviceResp->json('service');
        $serviceType = $service['service_type'] ?? null;

        $rules = ['service_id' => 'required|integer'];
        if ($serviceType === 'hotel') {
            $rules['check_in'] = 'required|date|after_or_equal:today';
            $rules['check_out'] = 'required|date|after:check_in';
        } elseif ($serviceType === 'event') {
            $rules['ticket_count'] = 'required|integer|min:1';
        } elseif ($serviceType === 'transportasi') {
            $rules['ticket_count'] = 'required|integer|min:1';
            $rules['trip_type'] = 'required|in:pergi,pulang-pergi';
            $rules['date_pergi'] = 'required|date|after_or_equal:today';
            if ($request->input('trip_type') === 'pulang-pergi') {
                $rules['date_pulang'] = 'required|date|after:date_pergi';
            }
        }

        $validated = $request->validate($rules);

        $postData = [
            'service_id' => $validated['service_id'],
            'check_in' => $validated['check_in'] ?? null,
            'check_out' => $validated['check_out'] ?? null,
            'ticket_count' => $validated['ticket_count'] ?? null,
            'trip_type' => $validated['trip_type'] ?? null,
            'date_pergi' => $validated['date_pergi'] ?? null,
            'date_pulang' => $validated['date_pulang'] ?? null,
        ];

        $response = Http::withToken($token)
            ->post($this->apiBaseUrl . '/customer/bookings/init', $postData);

        if ($response->failed()) {
            return back()->withErrors('Gagal membuat booking awal.');
        }

        $booking = $response->json('booking');

        return redirect()->route('customer.bookings.showPayment', ['bookingId' => $booking['id']])
            ->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');
    }

    // Tampilkan halaman pembayaran manual
    public function showPaymentForm($bookingId)
    {
        $token = session('token');
        $response = Http::withToken($token)->get($this->apiBaseUrl . "/customer/bookings/{$bookingId}");

        if ($response->failed()) {
            abort(404, 'Booking tidak ditemukan');
        }

        $booking = $response->json('data');

        // Hitung total harga manual (bisa sesuai kebutuhan)
        $serviceType = $booking['service']['service_type'] ?? null;
        $totalPrice = 0;
        if ($serviceType === 'hotel') {
            $checkIn = \Carbon\Carbon::parse($booking['check_in']);
            $checkOut = \Carbon\Carbon::parse($booking['check_out']);
            $days = $checkOut->diffInDays($checkIn);
            if ($days < 1) $days = 1;
            $totalPrice = $booking['service']['price'] * $days;
        } elseif ($serviceType === 'event') {
            $ticketCount = $booking['ticket_count'] ?? 1;
            $totalPrice = $booking['service']['price'] * $ticketCount;
        } elseif ($serviceType === 'transportasi') {
            $ticketCount = $booking['ticket_count'] ?? 1;
            $multiplier = ($booking['trip_type'] === 'pulang-pergi') ? 2 : 1;
            $totalPrice = $booking['service']['price'] * $ticketCount * $multiplier;
        }

        return view('customer.bookings.payment', compact('booking', 'totalPrice'));
    }

    // Proses update metode pembayaran manual
    public function processPayment(Request $request, $bookingId)
    {
        $token = session('token');
        $paymentMethod = $request->input('payment_method');

        if (!$paymentMethod) {
            return back()->withErrors('Metode pembayaran wajib dipilih.');
        }

        // Ambil payment_id dari booking terlebih dahulu
        $bookingResponse = Http::withToken($token)
            ->get($this->apiBaseUrl . "/customer/bookings/{$bookingId}");

        if ($bookingResponse->failed()) {
            return back()->withErrors('Booking tidak ditemukan.');
        }

        $booking = $bookingResponse->json('data');
        $paymentId = $booking['payment']['payment_id'] ?? $booking['payment']['id'] ?? null;

        if (!$paymentId) {
            return back()->withErrors('Payment ID tidak ditemukan.');
        }

        // Kirim data update metode pembayaran ke backend dengan PUT
        $response = Http::withToken($token)
            ->put($this->apiBaseUrl . "/customer/payments/{$paymentId}/update", [
                'method' => $paymentMethod,
            ]);

        if ($response->failed()) {
            return back()->withErrors('Gagal memproses pembayaran.');
        }

        // Redirect ke halaman proses pembayaran (halaman untuk konfirmasi manual)
        return redirect()->route('customer.bookings.processPaymentForm', ['bookingId' => $bookingId])
            ->with('success', 'Metode pembayaran berhasil dipilih. Silakan lanjutkan pembayaran secara manual.');
    }


    public function showProcessPaymentForm($bookingId)
    {
        $token = session('token');

        $response = Http::withToken($token)
            ->get($this->apiBaseUrl . "/customer/bookings/{$bookingId}");

        if ($response->failed()) {
            abort(404, 'Booking tidak ditemukan');
        }

        $booking = $response->json('data');

        return view('customer.bookings.processPayment', compact('booking'));
    }

    // Update status pembayaran manual (dibayar / tidak)
    public function updatePayment(Request $request, $paymentId)
    {
        $token = session('token');

        $validated = $request->validate([
            'method' => 'sometimes|required|string|max:255',
            'transaction_id' => 'sometimes|nullable|string|max:255',
            'paid' => 'sometimes|boolean',
        ]);

        $postData = [];
        if ($request->has('method')) {
            $postData['method'] = $validated['method'];
        }
        if ($request->has('transaction_id')) {
            $postData['transaction_id'] = $validated['transaction_id'];
        }
        if ($request->has('paid')) {
            $postData['paid'] = $validated['paid'];
        }

        $response = Http::withToken($token)
            ->put($this->apiBaseUrl . "/customer/payments/{$paymentId}/update", $postData);

        if ($response->failed()) {
            return back()->withErrors('Gagal memperbarui payment.');
        }

        $payment = $response->json('payment');

        // Jika sudah dibayar, langsung redirect ke halaman sukses pembayaran
        if ($payment['paid_at'] !== null) {
            return redirect()->route('customer.paymentSuccess', ['paymentId' => $paymentId]);
        }

        return redirect()->back()->with('success', 'Payment berhasil diperbarui.');
    }

    // Halaman sukses pembayaran
    public function paymentSuccess($paymentId)
    {
        $token = session('token');

        $response = Http::withToken($token)->get($this->apiBaseUrl . "/customer/payments/{$paymentId}/success");

        if ($response->failed()) {
            abort(404, 'Payment tidak ditemukan atau belum dibayar');
        }

        $payment = $response->json('payment');
        $booking = $response->json('booking');

        return view('customer.paymentSuccess', compact('payment', 'booking'));
    }

}
