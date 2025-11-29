<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Siswa | @yield('page_title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">

    {{-- HEADER --}}
    <header class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

            {{-- Logo + Identitas --}}
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-slate-900 text-white flex items-center justify-center font-semibold text-sm">
                    S
                </div>
                <div>
                    <p class="text-sm font-semibold">Panel Siswa</p>
                    <p class="text-xs text-slate-500">Booking & Akses fasilitas</p>
                </div>
            </div>

            {{-- NAV --}}
            <nav class="hidden md:flex items-center gap-4 text-xs font-semibold text-slate-600">
                <a href="{{ route('siswa.dashboard') }}" class="hover:text-slate-900">Dashboard</a>
                <a href="{{ route('siswa.bookings.index') }}" class="hover:text-slate-900">Booking</a>
                <a href="{{ route('siswa.bookings.create') }}" class="hover:text-slate-900">Buat</a>
                <a href="{{ route('siswa.reports.index') }}" class="hover:text-slate-900">Laporan</a>
            </nav>

            {{-- USER BOX --}}
            <div class="flex items-center gap-3">
                <div class="text-right leading-tight">
                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Siswa</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-xs px-3 py-2 rounded-md border border-slate-200 hover:bg-slate-100">
                        Keluar
                    </button>
                </form>
            </div>

        </div>
    </header>


    {{-- PAGE WRAP --}}
    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>

</body>
</html>
