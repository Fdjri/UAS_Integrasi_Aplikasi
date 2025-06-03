@extends('customer.layouts.landing')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-6">
    <h1 class="text-2xl font-bold mb-6">Pembayaran Berhasil</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Summary Pembayaran --}}
        <div class="bg-gray-200 rounded-lg p-6 flex flex-col items-center justify-center text-center">
            <h2 class="text-xl font-bold mb-4">Terbayar</h2>
            <p class="text-2xl mb-2">{{ ucfirst($payment['payment_status']) }}</p>
            <p class="text-lg mb-6">{{ $payment['method'] ?? '-' }}</p>
            <p class="text-3xl font-bold">Rp {{ number_format($payment['amount'] ?? 0, 0, ',', '.') }}</p>

            <a href="{{ route('customer.bookings.index') }}" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700">
                Check My Orders
            </a>
        </div>

        {{-- Detail Booking --}}
        <div class="bg-gray-200 rounded-lg p-6">
            <img src="{{ $booking['service']['photo_url'] ?? asset('images/hotel-placeholder.jpg') }}" alt="Service Photo" class="w-full h-48 object-cover rounded-lg shadow mb-4">

            <h2 class="text-lg font-semibold mb-2">{{ $booking['service']['title'] ?? '-' }}</h2>
            <p><strong>Alamat:</strong> {{ $booking['service']['service_address'] ?? '-' }}</p>

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
@endsection
