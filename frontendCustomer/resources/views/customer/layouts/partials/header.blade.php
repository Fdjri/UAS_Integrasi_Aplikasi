<nav style="background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center;">
  <a href="{{ route('landing') }}" style="font-weight:700; font-size:1.5rem; color:#2563eb; text-decoration:none;">
    TixGo
  </a>

  @if(session()->has('user'))
    <div style="position: relative; display: inline-block;">
      <button 
        id="profileBtn" 
        style="display:flex; align-items:center; gap:0.5rem; background:#eee; border-radius:9999px; padding:0.25rem 0.5rem; border:none; cursor:pointer;"
      >
        <div 
          style="
            width: 32px; height: 32px;
            background-color: #7c6740;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            user-select: none;
          "
        >
          {{ strtoupper(substr(session('user.username'), 0, 1)) }}
        </div>
        <svg style="width:16px; height:16px; color:#447fa1;" fill="currentColor" viewBox="0 0 20 20">
          <path d="M5.23 7.21a.75.75 0 011.06-.02L10 10.585l3.71-3.396a.75.75 0 111.04 1.08l-4.25 3.892a.75.75 0 01-1.04 0L5.25 8.27a.75.75 0 01-.02-1.06z"/>
        </svg>
      </button>

      <div id="profileDropdown" 
          class="dropdown"
          style="position:absolute; right:0; background:#fff; box-shadow:0 3px 10px rgba(0,0,0,0.1); border-radius:0.5rem; margin-top:0.5rem; min-width:200px; opacity:0; pointer-events:none; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease;">
        {{-- isi dropdown --}}
        <div style="padding:1rem; background:linear-gradient(90deg, #3b82f6, #2563eb); color:#fff; border-radius:0.5rem 0.5rem 0 0;">
          <div style="font-weight:bold;">{{ session('user.username') }}</div>
        </div>
        <ul style="list-style:none; padding:0; margin:0;">
          {{-- menu items --}}
          <li><a href="#" style="display:block; padding:0.75rem 1rem; color:#333; text-decoration:none; border-bottom:1px solid #eee;">Pesanan Kamu</a></li>
          <li><a href="#" style="display:block; padding:0.75rem 1rem; color:#333; text-decoration:none; border-bottom:1px solid #eee;">Simpan Data Penumpang</a></li>
          <li><a href="#" style="display:block; padding:0.75rem 1rem; color:#333; text-decoration:none; border-bottom:1px solid #eee;">Voucher Box</a></li>
          <li><a href="#" style="display:block; padding:0.75rem 1rem; color:#333; text-decoration:none; border-bottom:1px solid #eee;">Metode Pembayaran</a></li>
          <li><a href="#" style="display:block; padding:0.75rem 1rem; color:#333; text-decoration:none; border-bottom:1px solid #eee;">Daftar Refund</a></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
              @csrf
              <button
                type="submit"
                style="width:100%; text-align:left; padding:0.75rem 1rem; border:none; background:none; cursor:pointer; color:#333; font-size:1rem; font-family:inherit;">
                Keluar
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>

    <script>
      const btn = document.getElementById('profileBtn');
      const dropdown = document.getElementById('profileDropdown');

      btn.addEventListener('click', function(event) {
        event.stopPropagation();
        const isVisible = dropdown.style.opacity === '1';
        if (isVisible) {
          dropdown.style.opacity = '0';
          dropdown.style.pointerEvents = 'none';
          dropdown.style.transform = 'translateY(-10px)';
        } else {
          dropdown.style.opacity = '1';
          dropdown.style.pointerEvents = 'auto';
          dropdown.style.transform = 'translateY(0)';
        }
      });

      document.addEventListener('click', function() {
        dropdown.style.opacity = '0';
        dropdown.style.pointerEvents = 'none';
        dropdown.style.transform = 'translateY(-10px)';
      });
    </script>

  @else
    <div style="gap:1rem; display:flex;">
      <a href="{{ route('login') }}" style="background:#2563eb; color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none;">Masuk</a>
      <a href="{{ route('register') }}" style="border:1px solid #2563eb; color:#2563eb; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none;">Daftar</a>
    </div>
  @endif
</nav>
