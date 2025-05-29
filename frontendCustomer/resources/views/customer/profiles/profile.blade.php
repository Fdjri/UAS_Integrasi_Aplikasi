@extends('customer.applayout')

@section('title', 'Profile')

@section('content')
<h2 style="margin-bottom: 20px;">Profil Customer</h2>

{{-- Flash Message --}}
@if(session('success'))
    <div style="padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
        {{ $errors->first() }}
    </div>
@endif

<table style="width:100%; max-width:600px; border-collapse: collapse;">
    <tr>
        <td style="padding:8px; font-weight:bold;">Nama Lengkap</td>
        <td style="padding:8px;">{{ $profile['name'] ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding:8px; font-weight:bold;">Nomor HP</td>
        <td style="padding:8px;">{{ $profile['phone_number'] ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding:8px; font-weight:bold;">Alamat</td>
        <td style="padding:8px;">{{ $profile['address'] ?? '-' }}</td>
    </tr>
    <tr>
        <td style="padding:8px; font-weight:bold;">Tanggal Lahir</td>
        <td style="padding:8px;">
            {{ isset($profile['birth_date']) ? \Carbon\Carbon::parse($profile['birth_date'])->format('d-m-Y') : '-' }}
        </td>
    </tr>
</table>

<button id="btnEditProfile" style="margin-top:20px; padding:10px 15px; background:#007bff; color:#fff; border:none; border-radius:5px; cursor:pointer;">
    Edit Profile
</button>

{{-- Modal Edit Profile --}}
<div id="modalEditProfile" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); align-items: center; justify-content: center;">
    <div style="background: white; padding: 20px; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3>Edit Profile</h3>
        <form method="POST" action="{{ route('customer.profile.update.frontend') }}">
            @method('PUT')
            @csrf
            <label for="name">Nama Lengkap</label><br/>
            <input type="text" id="name" name="name" value="{{ old('name', $profile['name'] ?? '') }}" required style="width: 100%; padding: 8px; margin-bottom: 10px;"><br/>

            <label for="phone_number">Nomor HP</label><br/>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $profile['phone_number'] ?? '') }}" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br/>

            <label for="address">Alamat</label><br/>
            <textarea id="address" name="address" rows="3" style="width: 100%; padding: 8px; margin-bottom: 10px;">{{ old('address', $profile['address'] ?? '') }}</textarea><br/>

            <label for="birth_date">Tanggal Lahir</label><br/>
            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $profile['birth_date'] ?? '') }}" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br/>

            <div style="text-align: right;">
                <button type="submit" style="background:#007bff; color:#fff; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                    Simpan
                </button>
                <button type="button" id="btnCloseModal" style="background:#dc3545; color:#fff; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; margin-left:10px;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const btnEdit = document.getElementById('btnEditProfile');
    const modal = document.getElementById('modalEditProfile');
    const btnClose = document.getElementById('btnCloseModal');

    btnEdit.addEventListener('click', () => {
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
    });

    btnClose.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
@endsection
