@extends('admin.layout')

@section('content')
<style>
    /* Blur hanya untuk konten utama saat modal aktif */
    body.modal-open #main-wrapper {
        filter: blur(4px);
        user-select: none;
        pointer-events: none;
        transition: filter 0.3s ease;
    }

    /* Modal backdrop */
    #detailModal {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;

        opacity: 0;
        visibility: hidden;
        pointer-events: none;

        transition: opacity 0.3s ease, visibility 0.3s ease;
        z-index: 3000;
    }

    /* Modal visible */
    #detailModal.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    /* Modal content with pop-up scale animation & classic elegant style */
    #detailModal > div {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        width: 90%;
        max-width: 600px; /* Lebih lebar */
        font-family: 'Georgia', serif;
        position: relative;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        color: #4a403a;
        letter-spacing: 0.03em;
        box-sizing: border-box;

        transform: scale(0.7);
        transition: transform 0.3s ease;

        display: grid;
        grid-template-columns: max-content 1.5fr; /* kolom kiri & kanan */
        column-gap: 25px;
        row-gap: 15px;
        align-items: center;

        overflow-wrap: break-word;
        overflow: hidden;
    }

    /* Saat modal aktif, konten membesar */
    #detailModal.show > div {
        transform: scale(1);
    }

    /* Judul modal */
    #detailModal h3 {
        grid-column: 1 / -1;
        font-weight: 700;
        font-size: 24px;
        margin-bottom: 20px;
        border-bottom: 2px solid #b39c82;
        padding-bottom: 8px;
        color: #6f5846;
        text-align: center;
    }

    /* Label (kiri) */
    #detailModal p strong {
        font-weight: 700;
        color: #5b4d3d;
        text-align: right;
        user-select: text;
    }

    /* Isi (kanan) */
    #detailModal p {
        margin: 0;
        font-size: 16px;
        user-select: text;
    }

    /* Tombol tutup */
    #modalClose {
        grid-column: 1 / -1;
        margin-top: 25px;
        background: #b33527;
        color: #fff;
        border: none;
        padding: 12px 28px;
        border-radius: 7px;
        cursor: pointer;
        font-family: 'Georgia', serif;
        font-weight: 600;
        font-size: 16px;
        letter-spacing: 0.05em;
        box-shadow: 0 3px 7px rgba(179, 53, 39, 0.6);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        width: 100%;
    }

    #modalClose:hover {
        background-color: #8c271e;
        box-shadow: 0 4px 12px rgba(140, 39, 30, 0.8);
    }

    /* Table styling */
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

    /* Icon button style */
    .btn-detail.info-icon {
        background-color: #6f5846;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        border: none;
        color: white;
        font-family: 'Georgia', serif;
        font-weight: bold;
        font-size: 14px;
        line-height: 24px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        user-select: none;
        transition: background-color 0.3s ease;
    }

    .btn-detail.info-icon:hover {
        background-color: #8c6e4a;
    }

    .icon-text {
        user-select: none;
    }
</style>

<div id="main-wrapper">
    <h2 style="margin-bottom: 20px;">Management Customer</h2>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
            <tr>
                <td>{{ $customer->customerProfile->name ?? '-' }}</td>
                <td>{{ $customer->customerProfile->phone_number ?? '-' }}</td>
                <td>{{ $customer->email ?? '-' }}</td>
                <td class="text-center">
                    <button class="btn-detail info-icon" 
                        title="Lihat Detail"
                        data-name="{{ $customer->customerProfile->name ?? '-' }}"
                        data-email="{{ $customer->email ?? '-' }}"
                        data-address="{{ $customer->customerProfile->address ?? '-' }}"
                        data-phone="{{ $customer->customerProfile->phone_number ?? '-' }}"
                        data-birth="{{ $customer->customerProfile->birth_date ?? '-' }}"
                    >
                        <span class="icon-text">i</span>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">Tidak ada data customer.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
</div>

<!-- Modal -->
<div id="detailModal">
    <div>
        <h3>Detail Customer</h3>
        <p><strong>Nama:</strong> <span id="modalName"></span></p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Alamat:</strong> <span id="modalAddress"></span></p>
        <p><strong>Nomor Telepon:</strong> <span id="modalPhone"></span></p>
        <p><strong>Tanggal Lahir:</strong> <span id="modalBirth"></span></p>
        <button id="modalClose">Tutup</button>
    </div>
</div>

<script>
    const modal = document.getElementById('detailModal');
    const modalName = document.getElementById('modalName');
    const modalEmail = document.getElementById('modalEmail');
    const modalAddress = document.getElementById('modalAddress');
    const modalPhone = document.getElementById('modalPhone');
    const modalBirth = document.getElementById('modalBirth');
    const modalClose = document.getElementById('modalClose');

    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', () => {
            modalName.textContent = button.getAttribute('data-name');
            modalEmail.textContent = button.getAttribute('data-email');
            modalAddress.textContent = button.getAttribute('data-address');
            modalPhone.textContent = button.getAttribute('data-phone');
            modalBirth.textContent = button.getAttribute('data-birth');

            modal.classList.add('show');
            document.body.classList.add('modal-open');
        });
    });

    modalClose.addEventListener('click', () => {
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
        }
    });
</script>
@endsection
