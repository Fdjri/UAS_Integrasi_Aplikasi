<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Panel Admin</title>
<style>
    /* Reset & base */
    * {
        box-sizing: border-box;
        margin: 0; padding: 0;
    }
    body, html {
        height: 100%;
        font-family: 'Georgia', serif;
        background-color: #f5f3ef;
        color: #3a3a3a;
    }
    a {
        text-decoration: none;
        color: inherit;
    }
    ul {
        list-style: none;
    }

    /* Layout */
    .wrapper {
        display: flex;
        min-height: 100vh;
        flex-direction: row;
    }

    /* Sidebar fixed */
    aside.sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 260px;
        background: white;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-right: 1px solid #ddd6c7;
        z-index: 1000;
        overflow-y: auto;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    aside.sidebar.collapsed {
        transform: translateX(-260px);
    }
    aside.sidebar.expanded {
        transform: translateX(0);
    }

    .sidebar-header {
        padding: 20px 30px;
        border-bottom: 1px solid #ddd6c7;
        font-weight: 700;
        font-size: 20px;
        color: #4a403a;
        text-align: center;
        background-color: #fdfcf9;
    }

    /* Sidebar menu */
    nav.sidebar-menu {
        flex-grow: 1;
        padding: 15px 0;
        overflow-y: auto;
    }
    nav.sidebar-menu ul {
        padding-left: 0;
    }
    nav.sidebar-menu li {
        border-bottom: 1px solid #ddd6c7;
    }
    nav.sidebar-menu li a,
    nav.sidebar-menu li button {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        color: #5b4d3d;
        font-weight: 500;
        cursor: pointer;
        background: transparent;
        border: none;
        width: 100%;
        font-size: 16px;
        text-align: left;
        transition: background-color 0.2s ease;
    }
    nav.sidebar-menu li a:hover,
    nav.sidebar-menu li button:hover {
        background-color: #f1e9db;
        color: #6f5846;
    }

    /* Dropdown submenu */
    nav.sidebar-menu ul.submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        background-color: #faf7f1;
    }
    nav.sidebar-menu ul.submenu.show {
        max-height: 400px;
        border-top: 1px solid #ddd6c7;
    }
    nav.sidebar-menu ul.submenu li {
        border-bottom: none;
    }
    nav.sidebar-menu ul.submenu li a {
        padding-left: 50px;
        font-weight: 400;
        font-size: 14px;
        color: #7a6e5a;
    }
    nav.sidebar-menu ul.submenu li a:hover {
        background-color: #e7dfce;
        color: #6f5846;
    }

    /* Footer sidebar */
    aside.sidebar .sidebar-footer {
        padding: 20px 30px;
        border-top: 1px solid #ddd6c7;
        text-align: center;
    }
    aside.sidebar .sidebar-footer button {
        background-color: #b33527;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        transition: background-color 0.3s ease;
    }
    aside.sidebar .sidebar-footer button:hover {
        background-color: #8c271e;
    }

    /* Main content container */
    .main-wrapper {
        margin-left: 260px;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }
    .main-wrapper.expanded {
        margin-left: 0;
    }

    /* Main content */
    main.main-content {
        flex-grow: 1;
        padding: 30px 40px 80px 40px;
        overflow-y: auto;
        background-color: #f9f7f2;
    }

    /* Header */
    header.header {
        height: 60px;
        background: white;
        border-bottom: 1px solid #ddd6c7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        position: sticky;
        top: 0;
        z-index: 1010;
    }

    /* Sidebar toggle button */
    .sidebar-toggle-btn {
        font-size: 26px;
        background: none;
        border: none;
        cursor: pointer;
        color: #6f5846;
        padding: 0;
        margin-right: 20px;
        user-select: none;
    }

    .user-menu {
        position: relative;
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }
    .user-name {
        font-weight: 600;
        color: #5b4d3d;
        user-select: none;
    }
    .dropdown-icon {
        margin-left: 6px;
        border: solid #5b4d3d;
        border-width: 0 2px 2px 0;
        display: inline-block;
        padding: 3px;
        transform: rotate(45deg);
        transition: transform 0.3s ease;
    }
    .dropdown-icon.open {
        transform: rotate(-135deg);
    }

    /* Dropdown content */
    .user-dropdown {
        position: absolute;
        top: 48px;
        right: 0;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 8px;
        width: 160px;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
        z-index: 200;
    }
    .user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .user-dropdown a, .user-dropdown form button {
        display: block;
        padding: 12px 20px;
        color: #5b4d3d;
        text-align: left;
        font-weight: 500;
        font-size: 15px;
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .user-dropdown a:hover,
    .user-dropdown form button:hover {
        background-color: #f1e9db;
        color: #6f5846;
    }
    .user-dropdown form {
        margin: 0;
    }

    /* Footer */
    .footer {
        height: 60px;
        background-color: #fdfcf9;
        border-top: 2px solid #b39c82;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Georgia', serif;
        color: #7a6e5a;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.05em;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        flex-shrink: 0;
    }

    /* Responsive: mobile sidebar hidden by default */
    @media (max-width: 900px) {
        aside.sidebar {
            transform: translateX(-260px);
        }
        aside.sidebar.expanded {
            transform: translateX(0);
        }
        .main-wrapper {
            margin-left: 0 !important;
        }
        .main-wrapper.shifted {
            margin-left: 260px !important;
        }
    }
</style>
</head>
<body>
<div class="wrapper">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            Panel Admin
        </div>

        <nav class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>

                <li>
                    <button type="button" class="dropdown-btn" aria-expanded="false" aria-controls="customer-submenu">
                        Customer
                        <span class="dropdown-icon"></span>
                    </button>
                    <ul class="submenu" id="customer-submenu" aria-hidden="true">
                        <li><a href="{{ route('admin.customers.index') }}">Management Customer</a></li>
                        <li><a href="{{ route('admin.bookings.index') }}">Management Booking</a></li>
                        <li><a href="{{ route('admin.payments.index') }}">Management Payment</a></li>
                    </ul>
                </li>

                <li>
                    <button type="button" class="dropdown-btn" aria-expanded="false" aria-controls="services-submenu">
                        Services
                        <span class="dropdown-icon"></span>
                    </button>
                    <ul class="submenu" id="services-submenu" aria-hidden="true">
                        <li><a href="{{ route('admin.service_providers.index') }}">Management Service Providers</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Keluar</button>
            </form>
        </div>
    </aside>

    <div class="main-wrapper" id="mainWrapper">

        <header class="header">
            <button id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true" aria-controls="sidebar" class="sidebar-toggle-btn">&#9776;</button>
            <div class="user-menu" id="userMenu" tabindex="0" aria-haspopup="true" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=6f5846&color=fff&size=36" alt="Avatar" class="user-avatar" />
                <span class="user-name">{{ Auth::user()->username }}</span>
                <span class="dropdown-icon"></span>
                <div class="user-dropdown" id="userDropdown" role="menu" aria-label="User menu">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" role="menuitem">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <footer class="footer">
            <div class="footer-content">
                &copy; {{ date('Y') }} TixGo! • All rights reserved.
            </div>
        </footer>

    </div>

</div>

<script>
    // Sidebar dropdown toggle menu
    document.querySelectorAll('.dropdown-btn').forEach(button => {
        button.addEventListener('click', () => {
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', !expanded);

            const submenu = document.getElementById(button.getAttribute('aria-controls'));
            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
                submenu.setAttribute('aria-hidden', 'true');
            } else {
                submenu.classList.add('show');
                submenu.setAttribute('aria-hidden', 'false');
            }
        });
    });

    // User menu dropdown toggle
    const userMenu = document.getElementById('userMenu');
    const userDropdown = document.getElementById('userDropdown');

    userMenu.addEventListener('click', () => {
        const isExpanded = userMenu.getAttribute('aria-expanded') === 'true';
        userMenu.setAttribute('aria-expanded', !isExpanded);
        userDropdown.classList.toggle('show');
    });

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', (e) => {
        if (!userMenu.contains(e.target)) {
            userDropdown.classList.remove('show');
            userMenu.setAttribute('aria-expanded', 'false');
        }
    });

    // Sidebar toggle button functionality
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    const toggleBtn = document.getElementById('sidebarToggle');

    function closeSidebarMobile() {
        if (window.innerWidth <= 900) {
            sidebar.classList.remove('expanded');
            mainWrapper.classList.remove('shifted');
            toggleBtn.setAttribute('aria-expanded', false);
        }
    }

    function openSidebarMobile() {
        sidebar.classList.add('expanded');
        mainWrapper.classList.add('shifted');
        toggleBtn.setAttribute('aria-expanded', true);
    }

    // Inisialisasi sidebar sesuai ukuran layar saat load
    window.addEventListener('load', () => {
        if (window.innerWidth <= 900) {
            closeSidebarMobile();
        } else {
            sidebar.classList.remove('expanded');
            sidebar.classList.remove('collapsed');
            mainWrapper.classList.remove('shifted');
            toggleBtn.setAttribute('aria-expanded', true);
        }
    });

    // Toggle sidebar on button click
    toggleBtn.addEventListener('click', () => {
        const isMobile = window.innerWidth <= 900;
        if (isMobile) {
            if (sidebar.classList.contains('expanded')) {
                closeSidebarMobile();
            } else {
                openSidebarMobile();
            }
        } else {
            // Desktop toggle behavior
            const isCollapsed = sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('expanded', isCollapsed);
            toggleBtn.setAttribute('aria-expanded', !isCollapsed);
        }
    });

    // Tutup sidebar mobile jika klik di luar sidebar dan tombol toggle
    document.addEventListener('click', (e) => {
        const isMobile = window.innerWidth <= 900;
        if (isMobile) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                closeSidebarMobile();
            }
        }
    });

    // Resize event untuk reset class jika ubah ukuran layar
    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            sidebar.classList.remove('expanded');
            sidebar.classList.remove('collapsed');
            mainWrapper.classList.remove('shifted');
            toggleBtn.setAttribute('aria-expanded', true);
        } else {
            closeSidebarMobile();
        }
    });
</script>

</body>
</html>
