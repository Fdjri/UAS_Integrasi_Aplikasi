<nav style="background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); padding:1rem 2rem; display:flex; justify-content:space-between; align-items:center;">
  <a href="{{ route('landing') }}" style="font-weight:700; font-size:1.5rem; color:#2563eb; text-decoration:none;">
    TixGo
  </a>

  @guest
    <div style="gap:1rem; display:flex;">
      <a href="{{ route('login') }}" style="background:#2563eb; color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none;">Masuk</a>
      <a href="{{ route('register') }}" style="border:1px solid #2563eb; color:#2563eb; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none;">Daftar</a>
    </div>
  @else
    <div style="position:relative;">
      <button style="display:flex; align-items:center; gap:0.5rem; background:#eee; border-radius:9999px; padding:0.25rem 0.5rem; border:none; cursor:pointer;" onclick="document.getElementById('userDropdown').classList.toggle('hidden')">
        <div style="width:32px; height:32px; background:#ccc; border-radius:9999px; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#555;">
          {{ strtoupper(substr(session('user.name'), 0, 1)) }}
        </div>
        <svg style="width:16px; height:16px; color:#555;" fill="currentColor" viewBox="0 0 20 20"><path d="M5.23 7.21a.75.75 0 011.06-.02L10 10.585l3.71-3.396a.75.75 0 111.04 1.08l-4.25 3.892a.75.75 0 01-1.04 0L5.25 8.27a.75.75 0 01-.02-1.06z"/></svg>
      </button>

      <div id="userDropdown" class="hidden" style="position:absolute; right:0; background:#fff; box-shadow:0 3px 10px rgba(0,0,0,0.1); border-radius:0.5rem; margin-top:0.5rem; min-width:200px;">
        <div style="padding:1rem; background:linear-gradient(90deg, #3b82f6, #2563eb); color:#fff; border-radius:0.5rem 0.5rem 0 0;">
          <div style="font-weight:bold;">{{ session('user.name') }}</div>
          <div style="font-size:0.875rem;">Tier {{ session('user.tier') ?? 'Silver' }}</div>
          <div style="font-size:0.75rem;">{{ session('user.points') ?? 0 }}</div>
        </div>
        <ul style="list-style:none; padding:0; margin:0;">
          @foreach ([
            ['label' => 'Wishlist', 'badge' => 'Baru'],
            ['label' => 'Your Orders'],
            ['label' => 'Simpan Data Penumpang'],
            ['label' => 'Voucher Box'],
            ['label' => 'Metode Pembayaran'],
            ['label' => 'Daftar Refund'],
            ['label' => 'My Review'],
            ['label' => 'Claim Center'],
            ['label' => 'Pengaturan'],
          ] as $item)
            <li>
              <a href="#" style="display:block; padding:0.75rem 1rem; color:#333; text-decoration:none; border-bottom:1px solid #eee;">
                {{ $item['label'] }}
                @if (!empty($item['badge']))
                  <span style="background:#ef4444; color:#fff; font-size:0.65rem; padding:0.1rem 0.4rem; border-radius:9999px; margin-left:0.5rem;">{{ $item['badge'] }}</span>
                @endif
              </a>
            </li>
          @endforeach
          <li>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
              @csrf
              <button type="submit" style="width:100%; text-align:left; padding:0.75rem 1rem; border:none; background:none; cursor:pointer; color:#333;">
                Keluar
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>

    <script>
      document.addEventListener('click', function(e){
        const dropdown = document.getElementById('userDropdown');
        if (!dropdown) return;

        if (!dropdown.contains(e.target) && !e.target.closest('button')) {
          dropdown.classList.add('hidden');
        }
      });
    </script>
  @endguest
</nav>
