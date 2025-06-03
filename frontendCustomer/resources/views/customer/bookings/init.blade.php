@extends('customer.layouts.landing')

@section('title', 'Pilih Tanggal Booking')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md mt-6">
    <h1 class="text-2xl font-bold mb-6">Pilih Tanggal Booking untuk {{ $service['title'] ?? '-' }}</h1>

    <div class="flex flex-col md:flex-row gap-6 bg-white rounded-lg p-6 shadow">
        {{-- Gambar layanan --}}
        <img
            src="{{ $service['photo_url'] ?? asset('images/hotel-placeholder.jpg') }}"
            alt="{{ $service['title'] ?? 'Service Image' }}"
            class="w-full md:w-64 h-48 object-cover rounded-lg shadow"
        />

        {{-- Form pilih tanggal / tiket --}}
        <div class="flex flex-col flex-grow">
            <p class="text-gray-700 mb-4">{{ $service['description'] ?? '-' }}</p>

            {{-- FORM DENGAN METHOD POST BIASA DAN ACTION KE ROUTE --}}
            <form id="bookingForm" class="space-y-4" method="POST" action="{{ route('customer.bookings.init') }}">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service['service_id'] ?? old('service_id') ?? '' }}">

                @if($service['service_type'] === 'hotel')
                    <div>
                        <label for="check_in" class="block font-semibold mb-1">Tanggal Check In</label>
                        <input type="date" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               value="{{ old('check_in') }}">
                    </div>
                    <div>
                        <label for="check_out" class="block font-semibold mb-1">Tanggal Check Out</label>
                        <input type="date" id="check_out" name="check_out" min="{{ date('Y-m-d') }}" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               value="{{ old('check_out') }}">
                    </div>
                @elseif($service['service_type'] === 'event')
                    <div>
                        <label for="ticket_count" class="block font-semibold mb-1">Jumlah Tiket</label>
                        <input type="number" id="ticket_count" name="ticket_count" min="1" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               value="{{ old('ticket_count', 1) }}">
                    </div>
                @elseif($service['service_type'] === 'transportasi')
                    <div>
                        <label for="ticket_count" class="block font-semibold mb-1">Jumlah Tiket</label>
                        <input type="number" id="ticket_count" name="ticket_count" min="1" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               value="{{ old('ticket_count', 1) }}">
                    </div>
                    <div>
                        <label for="trip_type" class="block font-semibold mb-1">Tipe Perjalanan</label>
                        <select name="trip_type" id="trip_type" required
                                class="w-full border border-gray-300 rounded px-3 py-2"
                                onchange="handleTripTypeChange(this.value)">
                            <option value="">-- Pilih Tipe Perjalanan --</option>
                            <option value="pergi" {{ old('trip_type') === 'pergi' ? 'selected' : '' }}>Pergi</option>
                            <option value="pulang-pergi" {{ old('trip_type') === 'pulang-pergi' ? 'selected' : '' }}>Pulang Pergi</option>
                        </select>
                    </div>
                    <div id="date_pergi_container" class="mt-4">
                        <label for="date_pergi" class="block font-semibold mb-1">Tanggal Pergi</label>
                        <input type="date" id="date_pergi" name="date_pergi" min="{{ date('Y-m-d') }}" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               value="{{ old('date_pergi') }}">
                    </div>
                    <div id="date_pulang_container" class="mt-4 hidden">
                        <label for="date_pulang" class="block font-semibold mb-1">Tanggal Pulang</label>
                        <input type="date" id="date_pulang" name="date_pulang" min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               value="{{ old('date_pulang') }}">
                    </div>
                @endif

                <div class="flex gap-4">
                    <a href="{{ route('customer.detail', ['serviceId' => $service['service_id'] ?? '']) }}"
                       class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Kembali</a>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Book Now
                    </button>
                </div>
            </form>

            {{-- Tampilkan error message --}}
            @if ($errors->any())
                <div class="mt-4 text-red-600">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function handleTripTypeChange(value) {
    const pulangContainer = document.getElementById('date_pulang_container');
    const pulangInput = document.getElementById('date_pulang');
    if (value === 'pulang-pergi') {
        pulangContainer.classList.remove('hidden');
        pulangInput.setAttribute('required', 'required');
    } else {
        pulangContainer.classList.add('hidden');
        pulangInput.removeAttribute('required');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const tripTypeSelect = document.getElementById('trip_type');
    handleTripTypeChange(tripTypeSelect.value);
});
</script>
@endsection
