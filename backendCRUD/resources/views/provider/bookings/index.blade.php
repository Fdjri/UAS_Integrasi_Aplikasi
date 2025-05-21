@extends('provider.layout')

@section('content')
<style>
    /* Efek blur untuk container yang diblur */
    .blur {
        filter: blur(6px);
        pointer-events: none;
        user-select: none;
        transition: filter 0.3s ease;
    }

    /* Style tabel */
    table.management-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fdf9f3;
        color: #3c3c3c;
    }
    table.management-table th {
        background-color: #ebdfc9;
        font-weight: 600;
        padding: 10px 15px;
        border: 1px solid #d9d3b9;
        text-align: left;
    }
    table.management-table td {
        border: 1px solid #d9d3b9;
        padding: 10px 15px;
        vertical-align: middle;
    }
    table.management-table tbody tr:nth-child(even) {
        background-color: #f7f3e9;
    }
    table.management-table tbody tr:hover {
        background-color: #e5dfc4;
    }

    /* Tombol aksi */
    .action-btn {
        background-color: #5a4d3e;
        border: none;
        padding: 6px;
        border-radius: 5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 6px;
        transition: background-color 0.2s ease-in-out;
    }
    .action-btn.edit {
        background-color: #8a7a5b;
    }
    .action-btn:hover {
        filter: brightness(85%);
    }
    .action-btn svg {
        display: block;
        width: 20px;
        height: 20px;
    }

    /* Status dengan background */
    .status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        text-transform: capitalize;
        font-size: 0.85rem;
    }
    .status.pending {
        background-color: #d4a017;
    }
    .status.confirmed {
        background-color: #2e7d32;
    }
    .status.cancelled {
        background-color: #c62828;
    }
    .status.completed {
        background-color: #1565c0;
    }
    .status.failed {
        background-color: #6d6d6d;
    }

    /* Jarak judul ke tabel */
    .page-title {
        margin-bottom: 2rem;
    }

    /* Modal overlay */
    .modal-bg {
        position: fixed;
        inset: 0;
        background-color: rgba(0,0,0,0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        overflow-y: auto;
    }
    .modal-bg.active {
        display: flex;
    }

    /* Modal box */
    .modal-content {
        background-color: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.35);
        padding: 28px 32px;
        max-width: 440px;
        width: 90%;
        font-family: 'Georgia', serif;
        color: #3f2e25;
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
        line-height: 1.5;

        opacity: 0;
        transform: scale(0.8);
        animation: popup-zoom 0.3s forwards ease-in-out;
    }
    .modal-bg:not(.active) .modal-content {
        animation: none;
    }

    @keyframes popup-zoom {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Judul modal */
    .modal-content h2 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 18px;
        border-bottom: 1px solid #b99a79;
        padding-bottom: 6px;
        color: #5a422a;
    }

    /* Paragraf modal detail */
    .modal-content p {
        margin-bottom: 14px;
        font-size: 1rem;
    }

    /* Label tebal */
    .modal-content p strong {
        font-weight: 700;
        color: #422f1e;
    }

    /* Tombol close */
    .modal-close-btn {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 1.6rem;
        border: none;
        background: none;
        cursor: pointer;
        color: #8a6d53;
        transition: color 0.3s ease;
    }
    .modal-close-btn:hover {
        color: #5a422a;
    }

    /* Label & Input form */
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #5a422a;
    }
    select, input, textarea {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #c6ad92;
        font-size: 1rem;
        font-family: 'Georgia', serif;
        margin-bottom: 20px;
        box-sizing: border-box;
        color: #4a3622;
    }

    /* Tombol simpan dan batal */
    .btn-cancel, .btn-save {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        border: none;
        font-family: 'Georgia', serif;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(186,142,94,0.4);
        transition: background-color 0.3s ease;
    }
    .btn-cancel {
        background-color: #e7ddd2;
        color: #6d553b;
        margin-right: 12px;
    }
    .btn-cancel:hover {
        background-color: #d2c4b1;
    }
    .btn-save {
        background-color: #b9833b;
        color: #fff9f1;
        box-shadow: 0 6px 10px rgba(185,115,0,0.6);
    }
    .btn-save:hover {
        background-color: #997a31;
    }
</style>

<h1 class="page-title">Management Booking</h1>

<table class="management-table">
    <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th>Nama Customer</th>
            <th>Booking (Layanan)</th>
            <th>Status</th>
            <th style="width: 160px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings as $index => $booking)
        <tr>
            <td>{{ $bookings->firstItem() + $index }}</td>
            <td>{{ $booking->customer->customerProfile->name ?? '-' }}</td>
            <td>{{ $booking->service->title ?? '-' }}</td>
            <td>
                <span class="status {{ $booking->status }}">
                    {{ $booking->status }}
                </span>
            </td>
            <td>
                <button class="action-btn detail" onclick="openModal('detail-modal-{{ $booking->id }}')" title="Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24" fill="white" aria-hidden="true">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.75 15h-1.5v-6h1.5v6zm0-8h-1.5V7h1.5v2z"/>
                    </svg>
                </button>
                <button class="action-btn edit" onclick="openModal('edit-modal-{{ $booking->id }}')" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24" fill="white" aria-hidden="true">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-11.04a1.003 1.003 0 0 0 0-1.42l-2.5-2.5a1.003 1.003 0 0 0-1.42 0L14.06 6.21l3.75 3.75 2.9-2.75z"/>
                    </svg>
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align:center; padding: 20px; color: #666;">Belum ada data booking.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@foreach($bookings as $booking)
<div id="detail-modal-{{ $booking->id }}" class="modal-bg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content" role="document" aria-modal="true">
        <button class="modal-close-btn" aria-label="Close modal" onclick="closeModal('detail-modal-{{ $booking->id }}')">&times;</button>
        <h2>Detail Booking {{ $booking->customer->customerProfile->name }}</h2>
        <p><strong>Nama Customer:</strong> {{ $booking->customer->customerProfile->name ?? '-' }}</p>
        <p><strong>Layanan:</strong> {{ $booking->service->title ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
        <p><strong>Tanggal Booking:</strong> {{ $booking->booking_date }}</p>
        <p><strong>Dibuat pada:</strong> {{ $booking->created_at }}</p>
    </div>
</div>

<div id="edit-modal-{{ $booking->id }}" class="modal-bg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-content" role="document" aria-modal="true">
        <button class="modal-close-btn" aria-label="Close modal" onclick="closeModal('edit-modal-{{ $booking->id }}')">&times;</button>
        <h2>Edit Status Booking {{ $booking->customer->customerProfile->name }}</h2>
        <form action="{{ route('provider.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="status-{{ $booking->id }}">Status</label>
            <select name="status" id="status-{{ $booking->id }}" required>
                @foreach(['pending', 'confirmed', 'cancelled', 'completed', 'failed'] as $status)
                    <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" class="btn-cancel" onclick="closeModal('edit-modal-{{ $booking->id }}')">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    function openModal(id) {
        ['header', 'sidebar', 'main-wrapper', 'footer'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.classList.add('blur');
        });
        document.querySelectorAll('.modal-bg').forEach(modal => {
            if(modal.id === id){
                modal.classList.add('active');
                modal.focus();
            } else {
                modal.classList.remove('active');
            }
        });
    }

    function closeModal(id) {
        ['header', 'sidebar', 'main-wrapper', 'footer'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.classList.remove('blur');
        });
        const modal = document.getElementById(id);
        if(modal) {
            modal.classList.remove('active');
        }
    }

    window.addEventListener('click', function(event) {
        document.querySelectorAll('.modal-bg.active').forEach(modal => {
            if(event.target === modal) {
                closeModal(modal.id);
            }
        });
    });

    window.addEventListener('keydown', function(event) {
        if(event.key === 'Escape'){
            document.querySelectorAll('.modal-bg.active').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
</script>
@endsection
