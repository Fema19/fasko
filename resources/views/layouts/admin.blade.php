<!DOCTYPE html>
<html lang="en" x-data="{ openSidebar: false }" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="h-full bg-gray-100 flex">

    <!-- ===== SIDEBAR ===== -->
    <aside 
        class="fixed inset-y-0 left-0 bg-gray-900 w-64 text-white z-40 
               transform md:translate-x-0 transition-transform duration-300"
        :class="openSidebar ? 'translate-x-0' : '-translate-x-64 md:translate-x-0'">

        <div class="p-4 text-2xl font-bold border-b border-gray-700 text-center">
            Admin Panel
        </div>

        <nav class="mt-4 space-y-1">

            <a href="{{ route('admin.dashboard') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition">Dashboard</a>

            <a href="{{ route('admin.users.index') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition">Kelola Pengguna</a>

            <a href="{{ route('admin.rooms.index') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition">Kelola Ruangan</a>

            <a href="{{ route('admin.categories.index') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition">Kelola Kategori</a>

            <a href="{{ route('admin.facilities.index') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition">Kelola Fasilitas</a>

            <a href="{{ route('admin.repair-reports.index') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition">Laporan Kerusakan</a>

            {{-- ============================ --}}
            {{-- 🔥 Booking Requests (NEW MENU) --}}
            {{-- ============================ --}}
            <a href="{{ route('admin.bookings.requests') }}"
               class="block px-6 py-3 hover:bg-gray-800 transition flex justify-between items-center">

               Permintaan Booking

                @php
                    $pending = \App\Models\Booking::where('status','pending')->count();
                @endphp

                @if($pending > 0)
                <span class="bg-red-500 text-xs px-2 py-1 rounded">
                    {{ $pending }}
                </span>
                @endif
            </a>


            <!-- 🔥 LOGOUT (METHOD POST WAJIB) -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="block w-full text-left px-6 py-3 hover:bg-gray-800 transition text-red-400">
                    Logout
                </button>
            </form>

        </nav>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="flex-1 flex flex-col md:ml-64 transition-all">

        <!-- TOP BAR -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <button @click="openSidebar = !openSidebar" class="md:hidden text-gray-700 text-2xl">☰</button>

            <h1 class="text-xl md:text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>

            <div class="text-sm text-gray-600 hidden md:block">
                {{ now()->format('l, d F Y') }}
            </div>
        </header>

        <main class="p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>
