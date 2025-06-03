<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - TixGo Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-white font-sans">

<header class="bg-blue-600 text-white flex items-center justify-between px-6 py-3 font-bold text-xl">
    <a href="{{ route('customer.landingpage') }}" class="hover:underline">TixGo</a>

    <div class="relative" x-data="{ open: false }" x-cloak>
        <button
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-haspopup="true"
            class="w-9 h-9 rounded-full bg-yellow-700 text-white font-semibold focus:outline-none focus:ring-2 focus:ring-yellow-500 flex items-center justify-center"
            title="User menu"
        >
            {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
        </button>

        <div
            x-show="open"
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
            role="menu"
            aria-label="User menu"
        >
            <div class="px-4 py-3 text-yellow-800 font-bold border-b border-gray-100">
                {{ auth()->user()->username ?? 'User' }}
            </div>
            <a href="{{ route('customer.profile.frontend') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
            <a href="{{ route('customer.bookings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Pesanan Kamu</a>
            <a href="{{ route('customer.bookings.history') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100" role="menuitem">Riwayat</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 font-semibold" role="menuitem">Keluar</button>
            </form>
        </div>
    </div>
</header>

<div class="flex flex-grow bg-gray-100">
    <nav class="w-56 bg-white shadow-md rounded-lg m-6 p-6 flex flex-col gap-4">
        <strong class="text-lg text-gray-800 mb-4">{{ auth()->user()->username ?? 'Customer' }}</strong>
        <a href="{{ route('customer.profile.frontend') }}" class="{{ request()->routeIs('customer.profile.frontend') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-4 py-2 rounded font-semibold">Profile</a>
        <a href="{{ route('customer.bookings.index') }}" class="{{ request()->routeIs('customer.bookings.index') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-4 py-2 rounded font-semibold">Pemesanan</a>
        <a href="{{ route('customer.bookings.history') }}" class="{{ request()->routeIs('customer.bookings.history') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-200' }} px-4 py-2 rounded font-semibold">Riwayat</a>
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded font-semibold hover:bg-red-700">Keluar</button>
        </form>
    </nav>

    <main class="flex-grow bg-white rounded-lg shadow-md p-6 m-6 min-h-[500px]">
        @yield('content')
    </main>
</div>

<footer class="bg-gray-200 text-center p-4 text-gray-700 text-sm border-t border-gray-300">
    &copy; {{ date('Y') }} TixGo - All Rights Reserved
</footer>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
