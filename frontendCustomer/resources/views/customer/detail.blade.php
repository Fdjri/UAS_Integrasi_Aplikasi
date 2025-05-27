@extends('customer.layouts.landing')

@section('title', 'Detail Layanan')

@section('content')
<div style="max-width: 900px; margin: 40px auto; background-color: #d3d3d3; padding: 20px; display: flex; gap: 24px; align-items: center; border-radius: 12px;">

    <div style="flex-shrink: 0;">
        <img src="{{ asset('images/bg1.jpg') }}" alt="Monoloog Hotel Bekasi" style="width: 250px; border-radius: 12px;" />
    </div>

    <div style="flex-grow: 1; padding-left: 12px;">
        <h2 style="text-align: center; margin-bottom: 20px; font-weight: 700;">Monoloog Hotel Bekasi</h2>

        <div style="margin-bottom: 16px; font-weight: 600;">Address</div>
        <p style="margin-bottom: 24px;">Jl. Contoh Alamat No. 123, Bekasi, Jawa Barat</p>

        <div style="margin-bottom: 16px; font-weight: 600;">Description</div>
        <p style="margin-bottom: 24px;">
            Hotel bintang 5 dengan fasilitas lengkap dan pelayanan terbaik, cocok untuk bisnis dan liburan keluarga.
        </p>

        <div style="font-weight: 700; font-size: 1.5rem; margin-bottom: 20px;">Rp 250.600</div>

        <button 
            onclick="window.location.href='{{ url('customer/booking') }}'"
            style="background-color: #7a9eea; color: white; border: none; padding: 12px 28px; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 1rem;"
        >
            Book Now
        </button>
    </div>

</div>
@endsection
