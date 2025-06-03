@extends('customer.layouts.landing')

@section('title', 'Landing Page')

@section('content')
<div class="max-w-5xl mx-auto mt-8 mb-6 px-4">
    <nav class="flex justify-center space-x-6 bg-white rounded-xl shadow-md py-2" role="tablist" aria-label="Kategori layanan">
        <a href="{{ url('/customer/landing?service_type=hotel') }}"
           role="tab"
           aria-selected="{{ $serviceType === 'hotel' ? 'true' : 'false' }}"
           class="px-6 py-2 rounded-lg font-semibold text-gray-600 hover:bg-indigo-100 hover:text-indigo-700
           {{ $serviceType === 'hotel' ? 'bg-indigo-100 text-indigo-700 shadow' : '' }}">
            Hotel
        </a>
        <a href="{{ url('/customer/landing?service_type=event') }}"
           role="tab"
           aria-selected="{{ $serviceType === 'event' ? 'true' : 'false' }}"
           class="px-6 py-2 rounded-lg font-semibold text-gray-600 hover:bg-indigo-100 hover:text-indigo-700
           {{ $serviceType === 'event' ? 'bg-indigo-100 text-indigo-700 shadow' : '' }}">
            Event
        </a>
        <a href="{{ url('/customer/landing?service_type=transportasi') }}"
           role="tab"
           aria-selected="{{ $serviceType === 'transportasi' ? 'true' : 'false' }}"
           class="px-6 py-2 rounded-lg font-semibold text-gray-600 hover:bg-indigo-100 hover:text-indigo-700
           {{ $serviceType === 'transportasi' ? 'bg-indigo-100 text-indigo-700 shadow' : '' }}">
            Transportasi
        </a>
    </nav>
</div>

<div class="max-w-6xl mx-auto px-4 mb-12 grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" role="list" aria-live="polite" aria-atomic="true">
    @if(count($services) > 0)
        @foreach($services as $service)
            <article tabindex="0" role="listitem" class="bg-white rounded-xl shadow-md cursor-pointer flex flex-col transition-transform transform hover:scale-[1.03] hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-indigo-300">
                <div class="relative overflow-hidden rounded-t-xl h-44">
                    <img src="{{ $service['photo_url'] }}" alt="{{ $service['title'] }}" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-50"></div>
                </div>
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 truncate">{{ $service['title'] }}</h3>
                        <p class="text-red-600 font-semibold mt-1">Rp {{ number_format($service['price'], 0, ',', '.') }}</p>
                        <p class="text-gray-600 text-sm mt-1 truncate" title="{{ $service['service_address'] ?? 'Location not specified' }}">{{ $service['service_address'] ?? 'Location not specified' }}</p>
                    </div>
                    <button
                        type="button"
                        aria-label="Pesan {{ $service['title'] }}"
                        onclick="window.location.href='{{ isset($user) ? route('customer.detail', ['serviceId' => $service['service_id']]) : route('login') }}'"
                        class="mt-4 bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        Book Now
                    </button>
                </div>
            </article>
        @endforeach
    @else
        <article tabindex="0" role="listitem" class="bg-white rounded-xl shadow-md cursor-not-allowed flex flex-col">
            <div class="relative overflow-hidden rounded-t-xl h-44">
                <img src="{{ asset('images/bg1.jpg') }}" alt="No services available" class="w-full h-full object-cover opacity-50" />
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-30"></div>
            </div>
            <div class="p-4 flex-grow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-lg text-gray-400">No services available</h3>
                    <p class="text-gray-400 font-semibold mt-1">Rp 0</p>
                    <p class="text-gray-400 text-sm mt-1">Location not specified</p>
                </div>
                <button disabled aria-label="No services available" class="mt-4 bg-gray-400 text-white font-semibold py-2 rounded-lg cursor-not-allowed">
                    Book Now
                </button>
            </div>
        </article>
    @endif
</div>
@endsection
