@extends('layouts.admin')
@section('page_title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm">Ringkasan data dan aktivitas</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border shadow-sm">
        <p class="text-sm text-gray-500">Total Pengguna</p>
        <h2 class="text-3xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</h2>
    </div>
    <div class="bg-white p-5 rounded-xl border shadow-sm">
        <p class="text-sm text-gray-500">Total Ruangan</p>
        <h2 class="text-3xl font-bold text-gray-900">{{ $totalRooms ?? 0 }}</h2>
    </div>
    <div class="bg-white p-5 rounded-xl border shadow-sm">
        <p class="text-sm text-gray-500">Total Fasilitas</p>
        <h2 class="text-3xl font-bold text-gray-900">{{ $totalFacilities ?? 0 }}</h2>
    </div>
    <div class="bg-white p-5 rounded-xl border shadow-sm">
        <p class="text-sm text-gray-500">Booking Pending</p>
        <h2 class="text-3xl font-bold text-gray-900">{{ $pendingBookings ?? 0 }}</h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white p-5 rounded-xl border shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="font-semibold text-gray-800">Laporan Kerusakan Pending</p>
            <span class="text-sm text-gray-500">{{ $reports ?? 0 }}</span>
        </div>
        <p class="text-sm text-gray-600">Pantau laporan yang belum ditangani.</p>
    </div>

    <div class="bg-white p-5 rounded-xl border shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="font-semibold text-gray-800">Unit Fasilitas (opsional)</p>
            <span class="text-sm text-gray-500">Kapasitas / Unit</span>
        </div>
        <div class="text-sm text-gray-600 space-y-1">
            <p>Gunakan daftar fasilitas untuk memantau kapasitas & stok per item.</p>
        </div>
    </div>
</div>
@endsection
