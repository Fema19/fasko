<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fasilitas Sekolah') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
                <p class="text-sm text-gray-400 mt-1">Fasilitas Sekolah</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="font-semibold">Dashboard</span>
                </a>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Manajemen</p>
                    
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Pengguna
                    </a>

                    <a href="{{ route('admin.rooms.index') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Ruangan
                    </a>

                    <a href="{{ route('admin.facilities.index') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.facilities.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Fasilitas
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Kategori
                    </a>

                    <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.bookings.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Peminjaman
                    </a>

                    <a href="{{ route('admin.repair-reports.index') }}" class="block px-4 py-2 rounded {{ request()->routeIs('admin.repair-reports.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                        Laporan Perbaikan
                    </a>
                </div>
            </nav>

            <!-- User Profile & Logout -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded text-sm hover:bg-gray-800">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="text-sm text-gray-600">
                    {{ now()->format('l, d F Y') }}
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                <div class="p-6">
                    <!-- Alert Messages -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
