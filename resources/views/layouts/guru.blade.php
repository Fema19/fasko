<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Guru | @yield('page_title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --brand:#0f172a;
            --muted:#64748b;
            --surface:#ffffff;
            --border:#e2e8f0;
        }
        body{
            background: radial-gradient(circle at 20% 20%, #eef2ff 0, #f8fafc 35%, #f8fafc 70%, #e2e8f0 100%);
        }
        .page-shell{max-width:1080px;margin:0 auto;padding:2rem 1rem;}
        .nav-link{
            display:inline-flex;align-items:center;gap:6px;
            padding:0.55rem 0.75rem;border-radius:0.65rem;
            font-weight:600;font-size:12px;color:var(--muted);
            transition:all .15s ease;
        }
        .nav-link:hover{color:var(--brand);background:rgba(15,23,42,.06);}
        .nav-link.active{color:#fff;background:var(--brand);box-shadow:0 10px 30px rgba(15,23,42,.15);}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:0 10px 30px rgba(15,23,42,.05);}
        .card-padding{padding:1.15rem;}
        .muted{color:var(--muted);}
        .pill{display:inline-flex;align-items:center;gap:6px;font-size:11px;padding:0.35rem 0.65rem;border-radius:999px;border:1px solid var(--border);color:var(--muted);}
        .btn-ghost{padding:0.55rem 0.85rem;border:1px solid var(--border);border-radius:10px;font-size:12px;font-weight:600;color:var(--brand);background:#fff;transition:all .15s ease;}
        .btn-ghost:hover{background:rgba(15,23,42,.06);}
        .btn-dark{padding:0.65rem 1rem;border-radius:10px;font-size:13px;font-weight:700;color:#fff;background:var(--brand);box-shadow:0 10px 30px rgba(15,23,42,.15);}
        .input-base{width:100%;padding:0.65rem 0.75rem;border:1px solid var(--border);border-radius:10px;font-size:14px;background:#fff;outline:none;transition:border .15s ease, box-shadow .15s ease;}
        .input-base:focus{border-color:#94a3b8;box-shadow:0 0 0 4px rgba(148,163,184,.2);}
        .table-shell table{width:100%;border-collapse:collapse;}
        .table-shell th{font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);background:#f8fafc;padding:12px;text-align:left;}
        .table-shell td{padding:12px;font-size:14px;border-top:1px solid var(--border);}
        .badge-soft{padding:0.3rem 0.6rem;border-radius:999px;font-size:11px;font-weight:700;border:1px solid var(--border);color:var(--muted);}
        .facility-card{border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.06);background:#fff;}
        .tag{display:inline-flex;align-items:center;padding:0.35rem 0.6rem;border-radius:999px;border:1px solid var(--border);font-size:11px;color:var(--muted);}
    </style>
</head>
<body class="min-h-screen text-slate-800">
    <header class="bg-white/80 backdrop-blur border-b">
        <div class="page-shell flex items-center justify-between gap-4 py-4">
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-semibold text-sm shadow-sm">GURU</div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Panel Guru</p>
                    <p class="text-xs text-slate-500">Booking & fasilitas</p>
                </div>
            </div>
            <nav class="hidden md:flex items-center gap-2 text-xs font-semibold">
                <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard')?'active':'' }}">Dashboard</a>
                <a href="{{ route('guru.bookings.index') }}" class="nav-link {{ request()->routeIs('guru.bookings.*')?'active':'' }}">Booking</a>
                @if(Auth::user()->room_id)
                    <a href="{{ route('guru.bookings.requests') }}" class="nav-link {{ request()->routeIs('guru.bookings.requests')?'active':'' }}">Request</a>
                    <a href="{{ route('guru.bookings.history') }}" class="nav-link {{ request()->routeIs('guru.bookings.history')?'active':'' }}">History</a>
                    <a href="{{ route('guru.facilities.index') }}" class="nav-link {{ request()->routeIs('guru.facilities.*')?'active':'' }}">Fasilitas</a>
                    <a href="{{ route('guru.categories.index') }}" class="nav-link {{ request()->routeIs('guru.categories.*')?'active':'' }}">Kategori</a>
                @endif
                <a href="{{ route('guru.reports.index') }}" class="nav-link {{ request()->routeIs('guru.reports.*')?'active':'' }}">Laporan</a>
            </nav>
            <div class="flex items-center gap-3">
                <div class="text-right leading-tight">
                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Guru</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button class="btn-ghost">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="page-shell">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
