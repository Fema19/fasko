<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fasilitas Sekolah') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <a href="/" class="text-lg font-semibold text-slate-900">Fasilitas Sekolah</a>
            <div class="flex items-center gap-3">
                @auth
                    <span class="text-slate-600 text-sm hidden sm:inline">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
        @auth
            @if(Auth::user()->role === 'siswa')
            <div class="bg-slate-50 border-t border-slate-200">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap gap-3 text-xs font-semibold text-slate-600">
                    <a href="{{ route('siswa.dashboard') }}" class="px-3 py-1 rounded hover:bg-white">Dashboard</a>
                    <a href="{{ route('siswa.bookings.index') }}" class="px-3 py-1 rounded hover:bg-white">Booking</a>
                    <a href="{{ route('siswa.reports.index') }}" class="px-3 py-1 rounded hover:bg-white">Laporan</a>
                    @if(Route::has('siswa.facilities.index'))
                    <a href="{{ route('siswa.facilities.index') }}" class="px-3 py-1 rounded hover:bg-white">Fasilitas</a>
                    @endif
                </div>
            </div>
            @endif
        @endauth
    </header>

    <main class="max-w-6xl mx-auto p-6">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-slate-100 border-t border-slate-200 py-4 mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-slate-500 text-xs">© 2025 Fasilitas Sekolah. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
