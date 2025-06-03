@extends('customer.layouts.landing')

@section('title', 'Metode Pembayaran')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-6">

    <h1 class="text-2xl font-bold mb-6">Metode Pembayaran</h1>

    <div class="flex flex-col md:flex-row gap-6 bg-white rounded-lg p-6 shadow">

        {{-- Pilihan Metode Pembayaran --}}
        <div class="flex-1 bg-gray-200 rounded-lg p-6 shadow flex flex-col justify-center">
            <form id="payment-form" action="{{ route('customer.bookings.processPayment', $booking['id']) }}" method="POST">
                @csrf
                <h2 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h2>

                @php
                    $activePaymentMethod = old('payment_method', $booking['payment']['method'] ?? '');
                @endphp

                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="payment_method" value="credit_card" required
                            {{ $activePaymentMethod === 'credit_card' ? 'checked' : '' }}>
                        <span>Credit Card</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="payment_method" value="gopay"
                            {{ $activePaymentMethod === 'gopay' ? 'checked' : '' }}>
                        <span>GO-PAY</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_transfer"
                            {{ $activePaymentMethod === 'bank_transfer' ? 'checked' : '' }}>
                        <span>Bank Transfer</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="payment_method" value="indomaret"
                            {{ $activePaymentMethod === 'indomaret' ? 'checked' : '' }}>
                        <span>Indomaret</span>
                    </label>
                    {{-- Tambahkan metode lain sesuai kebutuhan --}}
                </div>

                @error('payment_method')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror

                <div class="mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full">
                        Bayar Rp {{ number_format($booking['payment']['amount'] ?? 0, 0, ',', '.') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Detail Pemesanan --}}
        <div class="flex-1 bg-gray-200 rounded-lg p-6 shadow flex items-center gap-6">
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

    </div>

</div>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentForm = document.getElementById('payment-form');
        const radios = paymentForm.elements['payment_method'];

        for (let radio of radios) {
            radio.addEventListener('change', function () {
                paymentForm.submit();
            });
        }
    });
</script> --}}

@endsection
