@extends('layouts.guru')
@section('page_title','Dashboard Guru')

@section('content')
<div class="card card-padding mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <p class="text-xs text-slate-500">Halo, {{ Auth::user()->name }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard Guru</h1>
            <p class="text-sm muted">
                Kelola booking Anda {{ Auth::user()->room_id ? 'dan ruangan yang Anda tangani' : 'dengan cepat' }}.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('guru.bookings.create') }}" class="btn-dark">+ Booking</a>
            @if(Auth::user()->room_id)
                <a href="{{ route('guru.bookings.requests') }}" class="btn-ghost">Request Masuk</a>
            @endif
        </div>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-3 mb-6">
    <div class="card card-padding space-y-2">
        <p class="text-xs muted">Peran</p>
        <p class="text-lg font-semibold text-slate-900">Guru</p>
        <span class="pill">{{ Auth::user()->room_id ? 'Penanggung jawab ruangan' : 'Guru (pemohon)' }}</span>
    </div>
    <div class="card card-padding space-y-2">
        <p class="text-xs muted">Ruangan</p>
        <p class="text-lg font-semibold text-slate-900">
            {{ Auth::user()->room_id ? Auth::user()->room->name : 'Tidak ada' }}
        </p>
        <p class="text-[11px] muted">{{ Auth::user()->room_id ? 'Dapat approve/menolak booking' : 'Hanya mengajukan booking' }}</p>
    </div>
    <div class="card card-padding space-y-3">
        <p class="text-xs muted">Akses cepat</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('guru.bookings.index') }}" class="btn-ghost">Booking Saya</a>
            @if(Auth::user()->room_id)
            <a href="{{ route('guru.bookings.history') }}" class="btn-ghost">History</a>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->room_id)
    <div class="card card-padding mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="text-xs muted">Penanggung jawab</p>
                <h2 class="text-lg font-semibold text-slate-900">{{ Auth::user()->room->name }}</h2>
                <p class="text-sm muted">Fasilitas: {{ Auth::user()->room->facilities->count() ?? 0 }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('guru.bookings.requests') }}" class="btn-dark">Kelola Request</a>
                <a href="{{ route('guru.facilities.index') }}" class="btn-ghost">Fasilitas</a>
            </div>
        </div>
    </div>
@endif

<div class="grid gap-3 md:grid-cols-3">
    <a href="{{ route('guru.bookings.index') }}" class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Booking Saya</p>
        <p class="text-sm muted">Lihat riwayat peminjaman</p>
    </a>
    <a href="{{ route('guru.bookings.create') }}" class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Buat Booking</p>
        <p class="text-sm muted">Ajukan pemakaian fasilitas</p>
    </a>
    @if(Auth::user()->room_id)
    <a href="{{ route('guru.bookings.requests') }}" class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Request Masuk</p>
        <p class="text-sm muted">Setujui atau tolak booking</p>
    </a>
    <a href="{{ route('guru.facilities.index') }}" class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Fasilitas Ruangan</p>
        <p class="text-sm muted">Kelola inventaris ruang Anda</p>
    </a>
    @endif
</div>
@endsection
