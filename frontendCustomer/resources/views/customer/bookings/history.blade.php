@extends('customer.applayout')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="min-h-screen flex flex-col">

  <div class="flex flex-grow bg-gray-100">

    {{-- Konten utama: Daftar riwayat pemesanan --}}
    <main class="flex-grow m-6 bg-gray-200 rounded-lg p-6 overflow-auto">
      <h1 class="text-3xl font-bold mb-8">Riwayat Pemesanan</h1>

      @if($bookings->isEmpty())
        <p class="text-gray-600">Belum ada riwayat pemesanan.</p>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($bookings as $booking)
            <div class="bg-white rounded-lg shadow p-4 flex gap-4">
              {{-- Foto layanan --}}
              <img
                src="{{ $booking->service->photo_url ?? asset('images/hotel-placeholder.jpg') }}"
                alt="{{ $booking->service->title ?? 'Service Image' }}"
                class="w-32 h-24 object-cover rounded-lg shadow"
              />

              {{-- Informasi booking --}}
              <div class="flex flex-col justify-between flex-grow">
                <div>
                  <h2 class="text-xl font-semibold">{{ $booking->service->title ?? '-' }}</h2>
                  <p class="text-gray-700">{{ $booking->service->service_address ?? '-' }}</p>
                </div>
                <div class="text-sm text-gray-600 mt-2">
                  <p><strong>Book Date:</strong> {{ optional($booking->booking_date)->format('d-m-Y') ?? '-' }}</p>
                  <p><strong>Status:</strong> <span class="capitalize">{{ $booking->status ?? '-' }}</span></p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </main>

  </div>
</div>
@endsection
