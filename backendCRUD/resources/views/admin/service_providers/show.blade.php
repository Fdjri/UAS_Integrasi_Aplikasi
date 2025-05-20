@extends('admin.layout')

@section('content')
<style>
    /* Gaya klasik untuk judul */
    h2 {
        font-family: 'Georgia', serif;
        color: #4a403a;
        margin-bottom: 20px;
    }

    /* Styling untuk detail profil dengan spacing antar baris */
    .profile-info p {
        font-family: 'Georgia', serif;
        font-size: 16px;
        margin: 12px 0;  /* Spacing antar baris */
        color: #5b4d3d;
    }

    /* Styling tabel layanan */
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Georgia', serif;
        margin-top: 30px;
    }
    thead tr {
        background-color: #f1e9db;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    /* Tombol kembali */
    .back-link {
        display: inline-block;
        margin-bottom: 15px;
        font-family: 'Georgia', serif;
        font-weight: 600;
        color: #6f5846;
        text-decoration: none;
        border: 1px solid #6f5846;
        padding: 6px 14px;
        border-radius: 6px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .back-link:hover {
        background-color: #6f5846;
        color: white;
        text-decoration: none;
    }
</style>

<div id="main-wrapper">
    <a href="{{ route('admin.service_providers.index') }}" class="back-link">&larr; Kembali ke Daftar Service Providers</a>

    <h2>Detail Service Provider</h2>

    <div class="profile-info">
        <p><strong>Company Name:</strong> {{ $provider->serviceProviderProfile->company_name ?? '-' }}</p>
        <p><strong>Service Type:</strong> {{ $provider->serviceProviderProfile->service_type ?? '-' }}</p>
        <p><strong>Business Phone:</strong> {{ $provider->serviceProviderProfile->business_phone ?? '-' }}</p>
        <p><strong>Business Address:</strong> {{ $provider->serviceProviderProfile->business_address ?? '-' }}</p>
    </div>

    <h3>Daftar Layanan</h3>

    @if($provider->services->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Judul Layanan</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($provider->services as $service)
                <tr>
                    <td>{{ $service->title }}</td>
                    <td>{{ $service->description ?? '-' }}</td>
                    <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                    <td>
                        @if($service->photo)
                            <img src="{{ asset('storage/' . $service->photo) }}" alt="{{ $service->title }}" style="max-height: 50px;">
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Service provider belum memiliki layanan apapun.</p>
    @endif
</div>
@endsection
