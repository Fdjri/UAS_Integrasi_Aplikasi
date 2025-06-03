@extends('customer.layouts.landing')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-6">

    {{-- Tampilkan pesan error jika ada --}}
    @if($errors->has('payment_error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Error Pembayaran:</strong>
            <p>{{ $errors->first('payment_error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Status & Payment Method & Countdown --}}
        <div class="bg-gray-200 rounded-lg p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold mb-4">Belum Bayar</h2>

                <h3 class="font-semibold mb-2">Metode Pembayaran</h3>
                <p class="mb-6 text-gray-700">{{ $booking['payment']['method'] ?? '-' }}</p>

                <h3 class="font-semibold mb-2">Waktu Pembayaran</h3>
                <p id="countdown" class="text-2xl font-bold text-red-600">00:00:00</p>
            </div>
        </div>

        {{-- Detail Pemesanan --}}
        <div class="bg-gray-200 rounded-lg p-6 flex items-center gap-6">
            <img 
                src="{{ $booking['service']['photo_url'] ?? asset('images/hotel-placeholder.jpg') }}" 
                alt="{{ $booking['service']['title'] ?? 'Service Image' }}" 
                class="w-40 h-40 object-cover rounded-lg shadow"
            />
            <div>
                <h2 class="font-semibold text-lg mb-2">{{ $booking['service']['title'] ?? '-' }}</h2>
                <p class="mb-1"><strong>Alamat:</strong> {{ $booking['service']['service_address'] ?? '-' }}</p>

                @if($booking['service']['service_type'] === 'hotel')
                    <p><strong>Check In:</strong> {{ $booking['check_in'] ? \Carbon\Carbon::parse($booking['check_in'])->format('d-m-Y') : '-' }}</p>
                    <p><strong>Check Out:</strong> {{ $booking['check_out'] ? \Carbon\Carbon::parse($booking['check_out'])->format('d-m-Y') : '-' }}</p>
                @elseif($booking['service']['service_type'] === 'event')
                    <p><strong>Jumlah Tiket:</strong> {{ $booking['ticket_count'] ?? '-' }}</p>
                @elseif($booking['service']['service_type'] === 'transportasi')
                    <p><strong>Jumlah Tiket:</strong> {{ $booking['ticket_count'] ?? '-' }}</p>
                    <p><strong>Tipe Perjalanan:</strong> {{ ucfirst($booking['trip_type'] ?? '-') }}</p>
                    <p><strong>Tanggal Pergi:</strong> {{ $booking['date_pergi'] ? \Carbon\Carbon::parse($booking['date_pergi'])->format('d-m-Y') : '-' }}</p>
                    @if(!empty($booking['trip_type']) && $booking['trip_type'] === 'pulang-pergi')
                        <p><strong>Tanggal Pulang:</strong> {{ $booking['date_pulang'] ? \Carbon\Carbon::parse($booking['date_pulang'])->format('d-m-Y') : '-' }}</p>
                    @endif
                @endif
            </div>
        </div>

        {{-- Total Bayar + Tombol "Saya sudah bayar" --}}
        <div class="bg-gray-200 rounded-lg p-6 flex flex-col justify-center items-center">
            <div class="mb-6 text-center">
                <h3 class="text-lg font-semibold mb-2">Total</h3>
                <p class="text-3xl font-bold">Rp {{ number_format($booking['payment']['amount'] ?? 0, 0, ',', '.') }}</p>
            </div>

            <form id="paid-form" action="{{ route('customer.payments.update', $booking['payment']['payment_id']) }}" method="POST" class="w-full max-w-xs mx-auto">
                @csrf
                @method('PUT')
                <input type="hidden" name="paid" value="1">
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg"
                >
                    Saya sudah bayar
                </button>
            </form>
        </div>

    </div>

</div>

{{-- Countdown Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const expirationSeconds = 86400;
        const bookingDateStr = '{{ $booking["booking_date"] ?? now()->toIso8601String() }}';
        const bookingDate = new Date(bookingDateStr);

        function updateCountdown() {
            const now = new Date();
            const elapsed = Math.floor((now - bookingDate) / 1000);
            let remaining = expirationSeconds - elapsed;

            if (remaining <= 0) {
                document.getElementById('countdown').textContent = 'Expired';
                clearInterval(timerInterval);
                return;
            }

            const hours = Math.floor(remaining / 3600);
            remaining %= 3600;
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;

            document.getElementById('countdown').textContent =
                `${hours.toString().padStart(2, '0')}:` +
                `${minutes.toString().padStart(2, '0')}:` +
                `${seconds.toString().padStart(2, '0')}`;
        }

        updateCountdown();
        const timerInterval = setInterval(updateCountdown, 1000);
    });
</script>

@endsection
