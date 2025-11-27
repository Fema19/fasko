<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Fasilitas Sekolah' }}</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-semibold">Fasilitas Sekolah</h1>

            <ul class="flex items-center gap-6">
                <li><a href="/" class="hover:text-blue-600">Home</a></li>
                <li><a href="{{ route('facilities.index') }}" class="hover:text-blue-600">Fasilitas</a></li>
                <li><a href="{{ route('categories.index') }}" class="hover:text-blue-600">Kategori</a></li>
                <li><a href="{{ route('bookings.index') }}" class="hover:text-blue-600">Bookings</a></li>
                <li><a href="{{ route('repair-reports.index') }}" class="hover:text-blue-600">Repair Reports</a></li>
                <li><a href="{{ route('messages.index') }}" class="hover:text-blue-600">Messages</a></li>

                {{-- Role Display --}}
                @auth
                    <li class="text-sm text-gray-600">
                        Role: <span class="font-semibold">{{ auth()->user()->role }}</span>
                    </li>

                    <form action="/logout" method="POST">
                        @csrf
                        <button class="px-3 py-1 bg-red-500 text-white rounded-md text-sm">
                            Logout
                        </button>
                    </form>
                @else
                    <li>
                        <a href="/login" class="px-3 py-1 bg-blue-500 text-white rounded-md text-sm">
                            Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
