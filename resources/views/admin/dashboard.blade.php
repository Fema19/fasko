@extends('layouts.admin')
@section('page_title', 'Dashboard Admin')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <nav class="text-sm text-gray-500">Home / Admin / Dashboard</nav>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Ruangan</div>
            <div class="text-2xl font-bold">{{ $totalRooms ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Fasilitas</div>
            <div class="text-2xl font-bold">{{ $totalFacilities ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Peminjaman Pending</div>
            <div class="text-2xl font-bold">{{ $pendingBookings ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Laporan Pending</div>
            <div class="text-2xl font-bold">{{ $reports ?? 0 }}</div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}" class="block bg-white p-4 rounded shadow hover:shadow-md">Kelola Pengguna</a>
        <a href="{{ route('admin.rooms.index') }}" class="block bg-white p-4 rounded shadow hover:shadow-md">Kelola Ruangan</a>
        <a href="{{ route('admin.facilities.index') }}" class="block bg-white p-4 rounded shadow hover:shadow-md">Kelola Fasilitas</a>
        <a href="{{ route('admin.categories.index') }}" class="block bg-white p-4 rounded shadow hover:shadow-md">Kelola Kategori</a>
    </div>
@endsection
