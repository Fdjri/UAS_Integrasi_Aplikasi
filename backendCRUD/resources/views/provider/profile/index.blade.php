@extends('provider.layout')

@section('content')
<style>
  /* Blur saat modal aktif hanya untuk background */
  .blur {
    filter: blur(6px);
    pointer-events: none;
    user-select: none;
    transition: filter 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  /* Container profil */
  #profile-container {
    max-width: 700px;
    margin: 40px auto;
    font-family: 'Georgia', serif;
    background: #f9f7f2;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    color: #4a403a;
    line-height: 1.6;
  }
  h2 {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 25px;
    border-bottom: 2px solid #b39c82;
    padding-bottom: 10px;
    color: #6f5846;
    text-align: center;
    letter-spacing: 0.05em;
  }
  .profile-item {
    margin-bottom: 18px;
  }
  .profile-label {
    font-weight: 700;
    color: #5b4d3d;
    margin-bottom: 4px;
    user-select: text;
  }
  .profile-value {
    font-size: 17px;
    color: #7a6e5a;
    user-select: text;
    white-space: pre-wrap;
  }
  .edit-btn {
    display: block;
    margin: 30px auto 0;
    background-color: #b33527;
    color: white;
    padding: 12px 28px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-family: 'Georgia', serif;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(179,53,39,0.7);
    transition: background-color 0.3s ease;
  }
  .edit-btn:hover {
    background-color: #8c271e;
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
    transition: opacity 0.35s ease;
  }
  .modal-bg.active {
    display: flex;
  }
  .modal-bg.fade-in {
    opacity: 1;
  }
  .modal-bg.fade-out {
    opacity: 0;
    pointer-events: none;
  }

  /* Modal box with pop-up animation */
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
    line-height: 1.5;

    opacity: 0;
    transform: scale(0.8);
    transition:
      opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1),
      transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .modal-bg.fade-in .modal-content {
    opacity: 1;
    transform: scale(1);
  }
  .modal-bg.fade-out .modal-content {
    opacity: 0;
    transform: scale(0.8);
  }

  /* Judul modal */
  .modal-content h3 {
    font-weight: 700;
    font-size: 24px;
    margin-bottom: 20px;
    border-bottom: 2px solid #b39c82;
    padding-bottom: 8px;
    text-align: center;
    color: #6f5846;
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
</style>

<div id="profile-container" role="main" aria-labelledby="profile-title">
  <h2 id="profile-title">Profil Service Provider</h2>

  <div class="profile-item">
    <div class="profile-label">Username</div>
    <div class="profile-value">{{ $user->username }}</div>
  </div>

  <div class="profile-item">
    <div class="profile-label">Email</div>
    <div class="profile-value">{{ $user->email }}</div>
  </div>

  <div class="profile-item">
    <div class="profile-label">Company Name</div>
    <div class="profile-value">{{ $profile->company_name ?? '-' }}</div>
  </div>

  <div class="profile-item">
    <div class="profile-label">Service Type</div>
    <div class="profile-value">{{ $profile->service_type ?? '-' }}</div>
  </div>

  <div class="profile-item">
    <div class="profile-label">Business Phone</div>
    <div class="profile-value">{{ $profile->business_phone ?? '-' }}</div>
  </div>

  <div class="profile-item">
    <div class="profile-label">Business Address</div>
    <div class="profile-value">{{ $profile->business_address ?? '-' }}</div>
  </div>

  <button class="edit-btn" id="openEditModalBtn" aria-haspopup="dialog" aria-controls="editProfileModal">Edit Profil</button>
</div>

<!-- Modal Edit Profile -->
<div id="editProfileModal" class="modal-bg" role="dialog" aria-modal="true" aria-labelledby="editProfileTitle" aria-hidden="true">
  <div class="modal-content">
    <button class="modal-close-btn" aria-label="Tutup modal" id="closeEditModalBtn">&times;</button>
    <h3 id="editProfileTitle">Edit Profil</h3>
    <form method="POST" action="{{ route('provider.profile.update') }}">
      @csrf
      @method('PUT')

      <label for="company_name">Company Name</label>
      <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $profile->company_name ?? '') }}" required>

      <label for="service_type">Service Type</label>
      <input type="text" name="service_type" id="service_type" value="{{ old('service_type', $profile->service_type ?? '') }}" required>

      <label for="business_phone">Business Phone</label>
      <input type="text" name="business_phone" id="business_phone" value="{{ old('business_phone', $profile->business_phone ?? '') }}" required>

      <label for="business_address">Business Address</label>
      <textarea name="business_address" id="business_address" rows="3" required>{{ old('business_address', $profile->business_address ?? '') }}</textarea>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>

      <label for="password">Password (Kosongkan jika tidak ingin diubah)</label>
      <input type="password" name="password" id="password" autocomplete="new-password">

      <label for="password_confirmation">Konfirmasi Password</label>
      <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password">

      <button type="submit" class="btn-save">Simpan Perubahan</button>
    </form>
  </div>
</div>

<script>
  const openBtn = document.getElementById('openEditModalBtn');
  const closeBtn = document.getElementById('closeEditModalBtn');
  const modal = document.getElementById('editProfileModal');

  function openModal() {
    modal.classList.remove('fade-out');
    modal.classList.add('active');

    setTimeout(() => {
      modal.classList.add('fade-in');
    }, 10);

    modal.setAttribute('aria-hidden', 'false');

    ['header', 'sidebar', 'main-wrapper', 'footer'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.classList.add('blur');
    });
  }

  function closeModal() {
    modal.classList.remove('fade-in');
    modal.classList.add('fade-out');
    modal.setAttribute('aria-hidden', 'true');

    setTimeout(() => {
      modal.classList.remove('active');
      ['header', 'sidebar', 'main-wrapper', 'footer'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('blur');
      });
    }, 350); // Durasi sesuai CSS transition
  }

  openBtn.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });
</script>
@endsection
