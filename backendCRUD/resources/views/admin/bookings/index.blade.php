@extends('admin.layout')

@section('content')
<style>
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
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        padding: 24px 28px;
        max-width: 400px;
        width: 90%;
        font-family: 'Georgia', serif;
        color: #222;
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
        line-height: 1.5;
    }

    /* Judul modal */
    .modal-content h2 {
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 16px;
    }

    /* Paragraf modal detail */
    .modal-content p {
        margin-bottom: 12px;
        font-size: 1rem;
    }

    /* Label tebal */
    .modal-content p strong {
        font-weight: 700;
        color: #000;
    }

    /* Tombol close */
    .modal-close-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 1.4rem;
        border: none;
        background: none;
        cursor: pointer;
        color: #555;
        transition: color 0.2s ease;
    }
    .modal-close-btn:hover {
        color: #000;
    }

    /* Label & Input form */
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #444;
    }
    select, input, textarea {
        width: 100%;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 1rem;
        font-family: inherit;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    /* Tombol simpan dan batal */
    .btn-cancel, .btn-save {
        padding: 10px 18px;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        border: none;
    }
    .btn-cancel {
        background-color: #eee;
        color: #555;
        margin-right: 10px;
        transition: background-color 0.2s ease;
    }
    .btn-cancel:hover {
        background-color: #ddd;
    }
    .btn-save {
        background-color: #2c7a2c;
        color: white;
        transition: background-color 0.2s ease;
    }
    .btn-save:hover {
        background-color: #235d23;
    }
</style>

<div class="container mx-auto px-4 py-6" id="main-wrapper">
    <h1 class="text-xl font-semibold page-title">Management Booking</h1>

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
                <td>{{ $booking->customer->username ?? '-' }}</td>
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

            {{-- Modal Detail --}}
            <div id="detail-modal-{{ $booking->id }}" class="modal-bg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-content" role="document">
                    <button class="modal-close-btn" aria-label="Close modal" onclick="closeModal('detail-modal-{{ $booking->id }}')">&times;</button>
                    <h2>Detail Booking #{{ $booking->id }}</h2>
                    <p><strong>Nama Customer:</strong> {{ $booking->customer->username ?? '-' }}</p>
                    <p><strong>Layanan:</strong> {{ $booking->service->title ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
                    <p><strong>Tanggal Booking:</strong> {{ $booking->booking_date }}</p>
                    <p><strong>Dibuat pada:</strong> {{ $booking->created_at }}</p>
                </div>
            </div>

            {{-- Modal Edit --}}
            <div id="edit-modal-{{ $booking->id }}" class="modal-bg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-content" role="document">
                    <button class="modal-close-btn" aria-label="Close modal" onclick="closeModal('edit-modal-{{ $booking->id }}')">&times;</button>
                    <h2>Edit Booking #{{ $booking->id }}</h2>
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
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
                        <div class="flex justify-end">
                            <button type="button" class="btn-cancel" onclick="closeModal('edit-modal-{{ $booking->id }}')">Batal</button>
                            <button type="submit" class="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding: 20px; color: #666;">Belum ada data booking.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function openModal(id) {
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
        const modal = document.getElementById(id);
        if(modal) {
            modal.classList.remove('active');
        }
    }

    window.addEventListener('click', function(event) {
        document.querySelectorAll('.modal-bg.active').forEach(modal => {
            if(event.target === modal) {
                modal.classList.remove('active');
            }
        });
    });

    window.addEventListener('keydown', function(event) {
        if(event.key === 'Escape'){
            document.querySelectorAll('.modal-bg.active').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });
</script>
@endsection
