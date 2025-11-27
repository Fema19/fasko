@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-12 text-center">
    <h1 class="text-4xl font-bold mb-4">Sistem Peminjaman Fasilitas Sekolah</h1>
    <p class="text-gray-600 mb-6">Pinjam dan kelola fasilitas sekolah seperti lapangan, ruang kelas, proyektor, dan lainnya dengan mudah.</p>

    <div class="flex justify-center gap-3 mb-8">
        <a href="{{ route('facilities.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded">Lihat Fasilitas</a>
        <a href="{{ route('categories.index') }}" class="px-6 py-3 border rounded">Kategori</a>
        <a href="{{ route('bookings.create') }}" class="px-6 py-3 bg-green-600 text-white rounded">Buat Booking</a>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-2xl font-semibold mb-3">Mengapa menggunakan sistem ini?</h2>
        <ul class="text-left list-disc pl-6 text-gray-700">
            <li>Kelola ketersediaan fasilitas secara terpusat.</li>
            <li>Proses peminjaman cepat dan terdokumentasi.</li>
            <li>Mudah melihat kategori dan detail fasilitas.</li>
        </ul>
    </div>
</div>
@endsection
