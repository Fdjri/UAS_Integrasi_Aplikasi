@extends('customer.applayout')

@section('title', 'Pemesanan')

@section('content')
<div class="bg-gray-300 rounded-lg p-6">
    <h3 class="font-semibold mb-4">Detail Pemesanan</h3>
    <div class="bg-gray-100 rounded-lg p-6 flex flex-col md:flex-row gap-6 items-center md:items-start">
      <img
        src="{{ asset('images/hotel-bekasi.jpg') }}"
        alt="Monoloog Hotel Bekasi"
        class="w-40 h-56 rounded-lg object-cover shadow-md"
      />
      <div class="flex flex-col gap-2 flex-grow">
        <h4 class="font-semibold text-lg">Monoloog Hotel Bekasi</h4>
        <p>Service_address</p>
        <p>Tanggal booking</p>
        <p>Detail Customer</p>
      </div>
    </div>

    <div class="flex justify-between items-center mt-6">
      <p class="font-bold text-xl">Rp. 250.600</p>
      <div class="flex gap-4">
        <button class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
          Hapus
        </button>
        <button class="bg-blue-400 hover:bg-blue-500 text-white font-semibold px-6 py-2 rounded-lg transition">
          Lanjut Bayar
        </button>
      </div>
    </div>
</div>
@endsection
