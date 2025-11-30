<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Guru | @yield('page_title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <header class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-lg bg-slate-900 text-white flex items-center justify-center font-semibold text-sm">G</div>
                <div>
                    <p class="text-sm font-semibold">Panel Guru</p>
                    <p class="text-xs text-slate-500">Booking & fasilitas</p>
                </div>
            </div>
            <nav class="hidden md:flex items-center gap-4 text-xs font-semibold text-slate-600">
                <a href="{{ route('guru.dashboard') }}" class="hover:text-slate-900">Dashboard</a>
                <a href="{{ route('guru.bookings.index') }}" class="hover:text-slate-900">Booking</a>
                @if(Auth::user()->room_id)
                    <a href="{{ route('guru.bookings.requests') }}" class="hover:text-slate-900">Request</a>
                    <a href="{{ route('guru.facilities.index') }}" class="hover:text-slate-900">Fasilitas</a>
                    <a href="{{ route('guru.categories.index') }}" class="hover:text-slate-900">Kategori</a>
                @endif
                <a href="{{ route('guru.reports.index') }}" class="hover:text-slate-900">Laporan</a>
            </nav>
            <div class="flex items-center gap-3">
                <div class="text-right leading-tight">
                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Guru</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button class="text-xs px-3 py-2 rounded-md border border-slate-200 hover:bg-slate-100">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
