@extends('admin.layout')

@section('content')
<style>
    /* Styling klasik & elegan untuk tabel */
    h2 {
        font-family: 'Georgia', serif;
        color: #4a403a;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Georgia', serif;
    }
    thead tr {
        background-color: #f1e9db;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
        vertical-align: middle;
    }
    td.text-center {
        text-align: center;
    }

    /* Tombol info bergaya ikon */
    .btn-detail.info-icon {
        background-color: #6f5846;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        border: none;
        color: white;
        font-family: 'Georgia', serif;
        font-weight: bold;
        font-size: 16px;
        line-height: 28px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        user-select: none;
        transition: background-color 0.3s ease;
    }
    .btn-detail.info-icon:hover {
        background-color: #8c6e4a;
        text-decoration: none;
        color: white;
    }
</style>

<div id="main-wrapper">
    <h2>Management Service Providers</h2>

    <table>
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Service Type</th>
                <th>Business Address</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($serviceProviders as $provider)
            <tr>
                <td>{{ $provider->serviceProviderProfile->company_name ?? '-' }}</td>
                <td>{{ $provider->serviceProviderProfile->service_type ?? '-' }}</td>
                <td>{{ $provider->serviceProviderProfile->business_address ?? '-' }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.service_providers.show', $provider->id) }}" 
                       class="btn-detail info-icon" 
                       title="Lihat Detail">
                        i
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">Tidak ada data service provider.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
