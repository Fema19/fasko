@extends('layouts.admin')

@section('page_title', 'Dashboard Admin')

@section('content')

<div class="mb-6">
    <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    <p class="text-gray-500 mt-1 text-sm">Home / Admin / Dashboard</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
        <p class="text-sm text-gray-500">Total Ruangan</p>
        <h2 class="text-3xl font-bold">{{ $totalRooms ?? 0 }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
        <p class="text-sm text-gray-500">Total Fasilitas</p>
        <h2 class="text-3xl font-bold">{{ $totalFacilities ?? 0 }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
        <p class="text-sm text-gray-500">Peminjaman Pending</p>
        <h2 class="text-3xl font-bold">{{ $pendingBookings ?? 0 }}</h2>
    </div>

    <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition">
        <p class="text-sm text-gray-500">Laporan Pending</p>
        <h2 class="text-3xl font-bold">{{ $reports ?? 0 }}</h2>
    </div>

</div>

<!-- Quick Action Buttons -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <a href="{{ route('admin.users.index') }}" class="block bg-white p-5 rounded-xl shadow hover:shadow-lg transition text-center font-medium">
        Kelola Pengguna
    </a>

    <a href="{{ route('admin.rooms.index') }}" class="block bg-white p-5 rounded-xl shadow hover:shadow-lg transition text-center font-medium">
        Kelola Ruangan
    </a>

    <a href="{{ route('admin.facilities.index') }}" class="block bg-white p-5 rounded-xl shadow hover:shadow-lg transition text-center font-medium">
        Kelola Fasilitas
    </a>

    <a href="{{ route('admin.categories.index') }}" class="block bg-white p-5 rounded-xl shadow hover:shadow-lg transition text-center font-medium">
        Kelola Kategori
    </a>

</div>

@endsection
