@extends('customer.layouts.landing')

@section('title', $service['title'] ?? 'Detail Service')

@section('content')
{{-- Tombol kembali --}}
<div class="max-w-[1100px] mx-auto mt-8 mb-3 px-5 flex">
    <button 
        onclick="window.history.back()" 
        class="border border-gray-500 text-gray-700 px-4 py-1.5 rounded-md text-sm font-medium hover:bg-gray-300 transition">
        &larr; Kembali
    </button>
</div>

{{-- Container detail --}}
<div class="max-w-[1100px] mx-auto bg-gray-200 rounded-xl p-8 flex gap-8 items-start flex-col md:flex-row px-5 md:px-8">

    {{-- Sidebar gambar --}}
    <div class="flex flex-col gap-4 items-center md:items-start">
        <img 
            src="{{ asset($service['photo_url'] ?? 'images/bg1.jpg') }}" 
            alt="{{ $service['title'] ?? 'Service Image' }}" 
            class="w-[280px] rounded-xl object-cover shadow-lg max-w-full" />
    </div>

    {{-- Info detail --}}
    <div class="flex-1">
        <h2 class="text-3xl font-extrabold mb-6 text-gray-900">
            {{ $service['title'] ?? 'Service Title' }}
        </h2>

        <p class="text-lg mb-6 text-gray-800">
            <strong>Address</strong><br>
            {{ $service['service_address'] ?? '-' }}
        </p>

        <p class="text-lg mb-10 text-gray-800">
            <strong>Description</strong><br>
            {{ $service['description'] ?? '-' }}
        </p>

        {{-- Footer info: price dan tombol --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 md:gap-0">
            <div class="text-2xl font-bold text-red-700">
                Rp {{ number_format($service['price'] ?? 0, 0, ',', '.') }}
            </div>
            <button 
                onclick="window.location.href='{{ route('customer.bookings.init.form', ['service_id' => $service['service_id']]) }}'" 
                class="bg-blue-400 hover:bg-blue-600 transition text-white font-semibold rounded-lg px-8 py-3 text-lg cursor-pointer">
                Book Now
            </button>
        </div>
    </div>
</div>
@endsection
