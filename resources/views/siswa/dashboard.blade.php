@extends('layouts.siswa')
@section('page_title','Dashboard Siswa')

@section('content')

<div class="card card-padding mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <p class="text-xs text-slate-500">Halo, {{ Auth::user()->name }}</p>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard Siswa</h1>
            <p class="text-sm muted">Ajukan peminjaman dan pantau status dengan cepat.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('siswa.bookings.create') }}" class="btn-dark">+ Booking</a>
            <a href="{{ route('siswa.bookings.index') }}" class="btn-ghost">Riwayat</a>
        </div>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-3 mb-6">
    <div class="card card-padding space-y-2">
        <p class="text-xs muted">Peran</p>
        <p class="text-lg font-semibold text-slate-900">Siswa</p>
        <span class="pill">Buat & pantau booking</span>
    </div>
    <div class="card card-padding space-y-2">
        <p class="text-xs muted">Booking Hari Ini</p>
        <p class="text-2xl font-semibold text-slate-900">{{ $todayBookings ?? 0 }}</p>
        <p class="text-[11px] muted">Peminjaman aktif</p>
    </div>
    <div class="card card-padding space-y-3">
        <p class="text-xs muted">Status Booking</p>
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="pill">Menunggu: {{ $pending ?? 0 }}</span>
            <span class="pill">Disetujui: {{ $approved ?? 0 }}</span>
            <span class="pill">Ditolak: {{ $rejected ?? 0 }}</span>
        </div>
    </div>
</div>

<div class="card card-padding mb-6">
    <p class="text-xs muted">Akses cepat</p>
    <div class="flex flex-wrap gap-2 mt-2">
        <a href="{{ route('siswa.bookings.create') }}" class="btn-dark">Buat Booking</a>
        <a href="{{ route('siswa.bookings.index') }}" class="btn-ghost">Riwayat Booking</a>
        <a href="{{ route('siswa.reports.index') }}" class="btn-ghost">Laporan Kendala</a>
    </div>
</div>

<div class="grid gap-3 md:grid-cols-3">
    <a href="{{ route('siswa.bookings.create') }}" 
        class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Booking Baru</p>
        <p class="text-sm muted">Ajukan pemakaian fasilitas</p>
    </a>

    <a href="{{ route('siswa.bookings.index') }}" 
        class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Riwayat Peminjaman</p>
        <p class="text-sm muted">Lihat status dan detail</p>
    </a>

    <a href="{{ route('siswa.reports.index') }}" 
        class="card card-padding hover:shadow-lg transition">
        <p class="font-semibold text-slate-900">Laporan Kendala</p>
        <p class="text-sm muted">Laporkan fasilitas bermasalah</p>
    </a>
</div>

@endsection
