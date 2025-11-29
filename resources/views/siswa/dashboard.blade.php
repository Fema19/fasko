@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
        <nav class="text-sm text-gray-500">Home / Siswa / Dashboard</nav>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Total Peminjaman</div>
            <div class="text-2xl font-bold">{{ $totalBookings ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Disetujui</div>
            <div class="text-2xl font-bold">{{ $approved ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <div class="text-sm text-gray-500">Pending</div>
            <div class="text-2xl font-bold">{{ $pending ?? 0 }}</div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4">
        <a href="{{ route('siswa.booking.index') }}" class="block bg-white p-4 rounded shadow">Peminjaman Saya</a>
        <a href="{{ route('siswa.report.index') }}" class="block bg-white p-4 rounded shadow">Laporan Kerusakan</a>
    </div>
</div>
@endsection
