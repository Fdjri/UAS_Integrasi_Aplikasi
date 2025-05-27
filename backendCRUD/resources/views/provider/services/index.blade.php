@extends('provider.layout')

@section('content')
<style>
  /* Style tabel */
  table.management-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Georgia', serif;
    background-color: #fdf9f3;
    color: #3c3c3c;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }
  table.management-table thead tr {
    background-color: #d9c8a3;
    font-weight: 700;
  }
  table.management-table th, table.management-table td {
    padding: 12px 18px;
    text-align: left;
  }
  table.management-table tbody tr {
    background-color: #faf3dd;
  }
  table.management-table tbody tr:nth-child(even) {
    background-color: #f9f4e8;
  }
  table.management-table tbody tr:hover {
    background-color: #e2d5a6;
  }

  /* Tombol tambah layanan */
  .btn-add-service {
    display: inline-flex;
    align-items: center;
    background-color: #b33527;
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-family: 'Georgia', serif;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(179, 53, 39, 0.7);
    margin-bottom: 20px;
    transition: background-color 0.3s ease;
    float: right;
  }
  .btn-add-service:hover {
    background-color: #8c271e;
  }
  .btn-add-service svg {
    margin-right: 8px;
    width: 20px;
    height: 20px;
  }

  /* Tombol aksi di tabel */
  .action-buttons {
    display: flex;
    gap: 8px;
  }
  .action-btn {
    background-color: #8c6e4a;
    border: none;
    padding: 6px;
    border-radius: 8px;
    cursor: pointer;
    color: white;
    font-weight: 600;
    font-family: 'Georgia', serif;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
  }
  .action-btn.detail {
    background-color: #6f5846;
  }
  .action-btn.edit {
    background-color: #8c6e4a;
  }
  .action-btn.delete {
    background-color: #b33527;
  }
  .action-btn:hover {
    filter: brightness(85%);
  }
  .action-btn svg {
    width: 18px;
    height: 18px;
  }

  /* Modal overlay */
  .modal-bg {
    position: fixed;
    inset: 0;
    background-color: rgba(0,0,0,0.4);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    pointer-events: auto;
    filter: none;
    opacity: 0;
    transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .modal-bg.active {
    display: flex;
    opacity: 1;
  }
  .modal-bg.fade-out {
    opacity: 0;
    pointer-events: none;
  }

  /* Modal box */
  .modal-content {
    background-color: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.35);
    padding: 28px 32px;
    max-width: 600px;
    width: 90%;
    font-family: 'Georgia', serif;
    color: #3f2e25;
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
    line-height: 1.6;
    transform: scale(0.85);
    opacity: 0;
    transition:
      opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1),
      transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .modal-bg.active .modal-content {
    opacity: 1;
    transform: scale(1);
  }
  .modal-bg.fade-out .modal-content {
    opacity: 0;
    transform: scale(0.85);
  }

  /* Modal titles */
  .modal-content h2, .modal-content h3 {
    font-weight: 700;
    font-size: 24px;
    margin-bottom: 20px;
    border-bottom: 2px solid #b39c82;
    padding-bottom: 8px;
    text-align: center;
    color: #6f5846;
  }

  /* Modal body spacing */
  .modal-content p {
    margin-bottom: 16px;
    font-size: 1.1rem;
  }

  /* Form labels & inputs */
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #5a422a;
  }
  input, select, textarea {
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

  /* Tombol simpan */
  .btn-save {
    background-color: #b33527;
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 8px;
    font-family: 'Georgia', serif;
    font-weight: 600;
    font-size: 18px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(179,53,39,0.7);
    transition: background-color 0.3s ease;
    width: 100%;
  }
  .btn-save:hover {
    background-color: #8c271e;
  }

  /* Tombol hapus di modal konfirmasi */
  .btn-confirm-delete {
    background-color: #b33527;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-family: 'Georgia', serif;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(179,53,39,0.7);
    transition: background-color 0.3s ease;
    margin-right: 12px;
  }
  .btn-confirm-delete:hover {
    background-color: #8c271e;
  }

  /* Tombol batal di modal konfirmasi */
  .btn-cancel-delete {
    background-color: #e7ddd2;
    color: #6d553b;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-family: 'Georgia', serif;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .btn-cancel-delete:hover {
    background-color: #d2c4b1;
  }
</style>

<h1 class="text-2xl font-semibold" style="color:#6f5846; margin-bottom: 1.5rem;">Management Service</h1>

<button class="btn-add-service" onclick="openModal('addServiceModal')" aria-label="Tambah Layanan Baru">
  <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/></svg>
  Tambah Layanan Baru
</button>

<table class="management-table">
  <thead>
    <tr>
      <th style="width: 40px;">No</th>
      <th>Judul</th>
      <th>Tipe Layanan</th>
      <th>Deskripsi</th>
      <th>Harga</th>
      <th style="width: 140px;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($services as $index => $service)
    <tr>
      <td>{{ $services->firstItem() + $index }}</td>
      <td>{{ $service->title }}</td>
      <td>{{ $service->service_type }}</td>
      <td>{{ Str::limit($service->description, 50, '...') }}</td>
      <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
      <td>
        <div class="action-buttons">
          <button class="action-btn detail" onclick="openModal('detailServiceModal-{{ $service->service_id }}')" title="Detail">
            <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.75 15h-1.5v-6h1.5v6zm0-8h-1.5V7h1.5v2z"/></svg>
          </button>
          <button class="action-btn edit" onclick="openModal('editServiceModal-{{ $service->service_id }}')" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-11.04a1.003 1.003 0 0 0 0-1.42l-2.5-2.5a1.003 1.003 0 0 0-1.42 0L14.06 6.21l3.75 3.75 2.9-2.75z"/></svg>
          </button>
          <button class="action-btn delete" onclick="openModal('deleteServiceModal-{{ $service->service_id }}')" title="Hapus">
            <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-4.5l-1-1z"/></svg>
          </button>
        </div>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="5" style="text-align:center; padding: 20px; color: #666;">Belum ada data layanan.</td>
    </tr>
    @endforelse
  </tbody>
</table>

<!-- Modal Tambah Service -->
<div id="addServiceModal" class="modal-bg" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="modal-content">
    <button class="modal-close-btn" aria-label="Tutup modal" onclick="closeModal('addServiceModal')">&times;</button>
    <h3>Tambah Layanan Baru</h3>
    <form method="POST" action="{{ route('provider.services.store') }}" enctype="multipart/form-data">
      @csrf
      <label for="title_add">Judul</label>
      <input type="text" name="title" id="title_add" required>

      <label for="description_add">Deskripsi</label>
      <textarea name="description" id="description_add"></textarea>

      <label for="price_add">Harga</label>
      <input type="number" name="price" id="price_add" required min="0" step="any">

      <label for="service_type_add">Tipe Layanan</label>
      <select name="service_type" id="service_type_add" required>
        <option value="" disabled selected>Pilih tipe layanan</option>
        <option value="hotel">Hotel</option>
        <option value="event">Event</option>
        <option value="transportasi">Transportasi</option>
      </select>

      <label for="service_address_add">Alamat Layanan</label>
      <input type="text" name="service_address" id="service_address_add">

      <label for="photo_add">Foto (opsional)</label>
      <input type="file" name="photo" id="photo_add" accept="image/*">

      <button type="submit" class="btn-save">Simpan</button>
    </form>
  </div>
</div>

<!-- Modal Detail Service -->
@foreach($services as $service)
<div id="detailServiceModal-{{ $service->service_id }}" class="modal-bg" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="modal-content">
    <button class="modal-close-btn" aria-label="Tutup modal" onclick="closeModal('detailServiceModal-{{ $service->service_id }}')">&times;</button>
    <h3>Detail Layanan</h3>
    <p><strong>Judul:</strong> {{ $service->title }}</p>
    <p><strong>Deskripsi:</strong> {{ $service->description ?? '-' }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($service->price, 0, ',', '.') }}</p>
    <p><strong>Tipe Layanan:</strong> {{ ucfirst($service->service_type ?? '-') }}</p>
    <p><strong>Alamat Layanan:</strong> {{ $service->service_address ?? '-' }}</p>
    <p><strong>Foto:</strong></p>
    @if($service->photo)
        <img src="{{ asset('storage/' . $service->photo) }}" alt="Foto layanan" style="max-width: 100%; max-height: 200px; border-radius: 12px; display: block; margin-top: 8px;">
    @else
        -
    @endif
  </div>
</div>
@endforeach

<!-- Modal Edit Service -->
@foreach($services as $service)
<div id="editServiceModal-{{ $service->service_id }}" class="modal-bg" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="modal-content">
    <button class="modal-close-btn" aria-label="Tutup modal" onclick="closeModal('editServiceModal-{{ $service->service_id }}')">&times;</button>
    <h3>Edit Layanan</h3>
    <form method="POST" action="{{ route('provider.services.update', $service->service_id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <label for="title_edit_{{ $service->service_id }}">Judul</label>
      <input type="text" name="title" id="title_edit_{{ $service->service_id }}" value="{{ old('title', $service->title) }}" required>

      <label for="description_edit_{{ $service->service_id }}">Deskripsi</label>
      <textarea name="description" id="description_edit_{{ $service->service_id }}">{{ old('description', $service->description) }}</textarea>

      <label for="price_edit_{{ $service->service_id }}">Harga</label>
      <input type="number" name="price" id="price_edit_{{ $service->service_id }}" value="{{ old('price', $service->price) }}" required min="0" step="any">

      <label for="service_type_edit_{{ $service->service_id }}">Tipe Layanan</label>
      <select name="service_type" id="service_type_edit_{{ $service->service_id }}" required>
        <option value="hotel" {{ old('service_type', $service->service_type) === 'hotel' ? 'selected' : '' }}>Hotel</option>
        <option value="event" {{ old('service_type', $service->service_type) === 'event' ? 'selected' : '' }}>Event</option>
        <option value="transportasi" {{ old('service_type', $service->service_type) === 'transportasi' ? 'selected' : '' }}>Transportasi</option>
      </select>

      <label for="service_address_edit_{{ $service->service_id }}">Alamat Layanan</label>
      <input type="text" name="service_address" id="service_address_edit_{{ $service->service_id }}" value="{{ old('service_address', $service->service_address) }}">

      <label for="photo_edit_{{ $service->service_id }}">Foto (opsional)</label>
      <input type="file" name="photo" id="photo_edit_{{ $service->service_id }}" accept="image/*">

      <button type="submit" class="btn-save">Simpan</button>
    </form>
  </div>
</div>
@endforeach

<!-- Modal Delete Confirmation -->
@foreach($services as $service)
<div id="deleteServiceModal-{{ $service->service_id }}" class="modal-bg" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1">
  <div class="modal-content" style="max-width: 400px;">
    <button class="modal-close-btn" aria-label="Tutup modal" onclick="closeModal('deleteServiceModal-{{ $service->service_id }}')">&times;</button>
    <h3>Konfirmasi Hapus</h3>
    <p>Apakah Anda yakin ingin menghapus layanan <strong>{{ $service->title }}</strong>?</p>
    <form method="POST" action="{{ route('provider.services.destroy', $service->service_id) }}">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-confirm-delete">Ya, Hapus</button>
      <button type="button" class="btn-cancel-delete" onclick="closeModal('deleteServiceModal-{{ $service->service_id }}')">Batal</button>
    </form>
  </div>
</div>
@endforeach

<script>
  function openModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
      modal.style.display = 'flex';
      modal.classList.add('active');
      modal.setAttribute('aria-hidden', 'false');

      ['header', 'sidebar', 'main-wrapper', 'footer'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.classList.add('blur');
      });
    }
  }

  function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
      modal.classList.remove('active');
      modal.setAttribute('aria-hidden', 'true');
      modal.style.display = 'none';

      ['header', 'sidebar', 'main-wrapper', 'footer'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.classList.remove('blur');
      });
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
    if(event.key === 'Escape') {
      document.querySelectorAll('.modal-bg.active').forEach(modal => {
        closeModal(modal.id);
      });
    }
  });
</script>
@endsection
