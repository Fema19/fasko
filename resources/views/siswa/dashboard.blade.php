@extends('layouts.siswa')
@section('page_title','Dashboard Siswa')

@section('content')

<div class="flex items-start justify-between mb-6">
    <div>
        <p class="text-xs text-slate-500">Halo, {{ Auth::user()->name }}</p>
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard Siswa</h1>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('siswa.bookings.create') }}" 
            class="px-3 py-2 bg-slate-900 text-white rounded-md text-xs">Buat Booking</a>
        <a href="{{ route('siswa.bookings.index') }}" 
            class="px-3 py-2 bg-slate-100 border text-xs rounded-md text-slate-800">Riwayat</a>
    </div>
</div>

{{-- 3 Box Ringkas --}}
<div class="grid gap-4 md:grid-cols-3 mb-6">
    <div class="p-4 bg-white border rounded-lg">
        <p class="text-xs text-slate-500">Role</p>
        <p class="text-lg font-semibold text-slate-900">Siswa</p>
    </div>

    <div class="p-4 bg-white border rounded-lg">
        <p class="text-xs text-slate-500">Booking Hari Ini</p>
        <p class="text-lg font-semibold text-slate-900">{{ $todayBookings ?? 0 }}</p>
        <p class="text-[11px] text-slate-500 mt-1">Peminjaman aktif</p>
    </div>

    <div class="p-4 bg-white border rounded-lg">
        <p class="text-xs text-slate-500">Status Booking</p>

        <div class="flex flex-wrap gap-2 mt-2 text-xs">
            <span class="px-2 py-1 rounded border text-slate-700">Menunggu: {{ $pending ?? 0 }}</span>
            <span class="px-2 py-1 rounded border text-green-700">Disetujui: {{ $approved ?? 0 }}</span>
            <span class="px-2 py-1 rounded border text-red-700">Ditolak: {{ $rejected ?? 0 }}</span>
        </div>
    </div>
</div>


{{-- Akses cepat --}}
<div class="bg-white border rounded-lg p-4 mb-6">
    <p class="text-xs text-slate-500">Akses cepat</p>

    <div class="flex gap-2 mt-2">
        <a href="{{ route('siswa.bookings.create') }}" 
           class="px-3 py-1.5 rounded-md border text-xs text-slate-700 hover:bg-slate-50">Buat Booking</a>

        <a href="{{ route('siswa.bookings.index') }}" 
           class="px-3 py-1.5 rounded-md border text-xs text-slate-700 hover:bg-slate-50">Riwayat Booking</a>

        <a href="{{ route('siswa.reports.index') }}" 
           class="px-3 py-1.5 rounded-md border text-xs text-slate-700 hover:bg-slate-50">Laporan Kendala</a>
    </div>
</div>


{{-- Menu Box List --}}
<div class="grid gap-3 md:grid-cols-3">
    <a href="{{ route('siswa.bookings.create') }}" 
        class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Booking Baru</p>
        <p class="text-xs text-slate-500">Ajukan pemakaian fasilitas</p>
    </a>

    <a href="{{ route('siswa.bookings.index') }}" 
        class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Riwayat Peminjaman</p>
        <p class="text-xs text-slate-500">Lihat status dan detail</p>
    </a>

    <a href="{{ route('siswa.reports.index') }}" 
        class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Laporan Kendala</p>
        <p class="text-xs text-slate-500">Laporkan fasilitas bermasalah</p>
    </a>
</div>

@endsection
