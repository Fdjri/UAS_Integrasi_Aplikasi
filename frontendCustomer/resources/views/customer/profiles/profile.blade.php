@extends('customer.applayout')

@section('title', 'Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Profil Customer</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <table class="w-full border-separate border-spacing-y-4">
        <tbody>
            <tr>
                <td class="font-semibold w-40">Nama Lengkap</td>
                <td>{{ $profile['name'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="font-semibold">Nomor HP</td>
                <td>{{ $profile['phone_number'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="font-semibold align-top">Alamat</td>
                <td class="whitespace-pre-wrap">{{ $profile['address'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="font-semibold">Tanggal Lahir</td>
                <td>{{ isset($profile['birth_date']) ? \Carbon\Carbon::parse($profile['birth_date'])->format('d-m-Y') : '-' }}</td>
            </tr>
        </tbody>
    </table>

    <button
        id="btnEditProfile"
        class="mt-6 px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
    >
        Edit Profile
    </button>
</div>

{{-- Modal Edit Profile --}}
<div
    id="modalEditProfile"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300"
>
    <div
        class="bg-white rounded-lg max-w-lg w-full p-8 shadow-lg transform translate-y-10 transition-transform duration-300"
    >
        <h3 class="text-xl font-bold mb-6">Edit Profile</h3>
        <form method="POST" action="{{ route('customer.profile.update.frontend') }}" class="space-y-4">
            @method('PUT')
            @csrf
            <div>
                <label for="name" class="block mb-1 font-semibold text-gray-700">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $profile['name'] ?? '') }}"
                    required
                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>
            <div>
                <label for="phone_number" class="block mb-1 font-semibold text-gray-700">Nomor HP</label>
                <input
                    type="text"
                    id="phone_number"
                    name="phone_number"
                    value="{{ old('phone_number', $profile['phone_number'] ?? '') }}"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>
            <div>
                <label for="address" class="block mb-1 font-semibold text-gray-700">Alamat</label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >{{ old('address', $profile['address'] ?? '') }}</textarea>
            </div>
            <div>
                <label for="birth_date" class="block mb-1 font-semibold text-gray-700">Tanggal Lahir</label>
                <input
                    type="date"
                    id="birth_date"
                    name="birth_date"
                    value="{{ old('birth_date', $profile['birth_date'] ?? '') }}"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button
                    type="submit"
                    class="bg-blue-600 text-white rounded-md px-6 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    Simpan
                </button>
                <button
                    type="button"
                    id="btnCloseModal"
                    class="bg-red-600 text-white rounded-md px-6 py-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                >
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
        modal.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-10');
        modal.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
    });

    btnClose.addEventListener('click', () => {
        modal.classList.add('opacity-0', 'pointer-events-none', '-translate-y-10');
        modal.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
    });

    // Tutup modal saat klik di luar konten
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            btnClose.click();
        }
    });
</script>
@endsection
