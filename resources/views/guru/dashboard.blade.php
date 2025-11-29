@extends('layouts.guru')
@section('page_title','Dashboard Guru')

@section('content')
<div class="flex items-start justify-between mb-6">
    <div>
        <p class="text-xs text-slate-500">Halo, {{ Auth::user()->name }}</p>
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard Guru</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('guru.bookings.create') }}" class="px-3 py-2 bg-slate-900 text-white rounded-md text-xs">Buat Booking</a>
        @if(Auth::user()->room_id)
            <a href="{{ route('guru.bookings.requests') }}" class="px-3 py-2 bg-slate-100 text-slate-800 rounded-md text-xs border">Request</a>
        @endif
    </div>
</div>

<div class="grid gap-4 md:grid-cols-3 mb-6">
    <div class="p-4 bg-white border rounded-lg">
        <p class="text-xs text-slate-500">Peran</p>
        <p class="text-lg font-semibold text-slate-900">Guru</p>
    </div>
    <div class="p-4 bg-white border rounded-lg">
        <p class="text-xs text-slate-500">Ruangan</p>
        <p class="text-lg font-semibold text-slate-900">
            {{ Auth::user()->room_id ? Auth::user()->room->name : 'Tidak ada' }}
        </p>
        <p class="text-[11px] text-slate-500 mt-1">{{ Auth::user()->room_id ? 'Bisa approve booking' : 'Hanya mengajukan booking' }}</p>
    </div>
    <div class="p-4 bg-white border rounded-lg">
        <p class="text-xs text-slate-500">Akses cepat</p>
        <div class="flex gap-2 mt-2">
            <a href="{{ route('guru.bookings.index') }}" class="px-3 py-1.5 rounded-md border text-xs text-slate-700 hover:bg-slate-50">Booking</a>
            @if(Auth::user()->room_id)
            <a href="{{ route('guru.bookings.requests') }}" class="px-3 py-1.5 rounded-md border text-xs text-slate-700 hover:bg-slate-50">Approve</a>
            @endif
        </div>
    </div>
</div>

@if(Auth::user()->room_id)
    <div class="bg-white border rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500">Penanggung jawab</p>
                <h2 class="text-lg font-semibold text-slate-900">{{ Auth::user()->room->name }}</h2>
            </div>
            <a href="{{ route('guru.bookings.requests') }}" class="text-xs px-3 py-2 rounded-md border text-slate-700 hover:bg-slate-50">Kelola request</a>
        </div>
    </div>
@endif

<div class="grid gap-3 md:grid-cols-3">
    <a href="{{ route('guru.bookings.index') }}" class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Booking Saya</p>
        <p class="text-xs text-slate-500">Lihat riwayat peminjaman</p>
    </a>
    <a href="{{ route('guru.bookings.create') }}" class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Buat Booking</p>
        <p class="text-xs text-slate-500">Ajukan pemakaian fasilitas</p>
    </a>
    @if(Auth::user()->room_id)
    <a href="{{ route('guru.bookings.requests') }}" class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Request Masuk</p>
        <p class="text-xs text-slate-500">Setujui atau tolak booking</p>
    </a>
    <a href="{{ route('guru.facilities.index') }}" class="p-4 bg-white border rounded-lg hover:bg-slate-50">
        <p class="font-semibold text-slate-900">Fasilitas Ruangan</p>
        <p class="text-xs text-slate-500">Kelola inventaris ruang Anda</p>
    </a>
    @endif
</div>
@endsection
