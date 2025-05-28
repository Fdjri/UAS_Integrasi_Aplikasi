<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - TixGo Customer</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header input[type="text"] {
            width: 300px;
            padding: 6px 10px;
            border-radius: 5px;
            border: none;
            outline: none;
        }
        header .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #004a99;
        }

        .container {
            display: flex;
            flex-grow: 1;
            padding: 20px;
            gap: 20px;
            background-color: #eee;
        }
        nav.sidebar {
            width: 220px;
            background: #f7f7f7;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        nav.sidebar strong {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
        }
        nav.sidebar a {
            display: block;
            padding: 10px 14px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        nav.sidebar a.active,
        nav.sidebar a:hover {
            background-color: #007bff;
            color: white;
        }
        nav.sidebar form.logout-form {
            margin-top: auto;
        }
        nav.sidebar button.logout-btn {
            width: 100%;
            padding: 10px 0;
            border: none;
            background-color: #dc3545;
            color: white;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
        }

        main.content {
            flex-grow: 1;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgb(0 0 0 / 0.1);
            min-height: 500px;
        }

        footer {
            padding: 10px 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
            background: #f0f0f0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <header>
        <div>TixGo</div>
        <input type="text" placeholder="Staycation di Bali" aria-label="Cari layanan" />
        <div class="user-avatar" title="User Profile"></div>
    </header>

    <div class="container">
        <nav class="sidebar" aria-label="Menu Navigasi Customer">
            <strong>{{ auth()->user()->username ?? 'Customer' }}</strong>
            <a href="{{ route('customer.profile.frontend') }}" class="{{ request()->routeIs('customer.profile.frontend') ? 'active' : '' }}">Profile</a>
            <a href="{{ route('customer.bookings.index') }}" class="{{ request()->routeIs('customer.bookings.index') ? 'active' : '' }}">Pemesanan</a>
            <a href="{{ route('customer.bookings.history') }}" class="{{ request()->routeIs('customer.bookings.history') ? 'active' : '' }}">Riwayat</a>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn" aria-label="Logout">Keluar</button>
            </form>
        </nav>

        <main class="content" role="main">
            @yield('content')
        </main>
    </div>

    <footer>
        &copy; {{ date('Y') }} TixGo - All Rights Reserved
    </footer>
</body>
</html>
