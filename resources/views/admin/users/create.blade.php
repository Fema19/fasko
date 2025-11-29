@extends('layouts.admin')
@section('page_title', 'Tambah Pengguna')

@section('content')

<h1 class="text-3xl font-bold mb-6">Tambah Pengguna</h1>

<form method="POST" action="{{ route('admin.users.store') }}" class="bg-white p-6 rounded shadow max-w-xl">
    @csrf

    {{-- Nama --}}
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}"
               class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Email --}}
    <div class="mb-4">
        <label class="block text-gray-700 font-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Role --}}
    <div class="mb-4">
        <label class="block font-semibold text-gray-700">Role</label>
        <select name="role" onchange="toggleRoomSelect()" id="roleInput"
                class="w-full border rounded px-3 py-2" required>
            <option value="">Pilih Role</option>
            <option value="guru"  {{ old('role')=='guru'?'selected':'' }}>Guru</option>
            <option value="siswa" {{ old('role')=='siswa'?'selected':'' }}>Siswa</option>
        </select>
    </div>

    {{-- Room (muncul hanya jika role == guru) --}}
    <div class="mb-4 {{ old('role')=='guru' ? '' : 'hidden' }}" id="roomField">
        <label class="block font-semibold text-gray-700">Ruangan yang dikelola (opsional)</label>
        <select name="room_id" class="w-full border rounded px-3 py-2">
            <option value="">Guru Biasa (tanpa ruangan)</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}" {{ old('room_id')==$room->id?'selected':'' }}>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
        <p class="text-sm text-gray-600 mt-1">Kosongkan jika guru tidak mengelola ruangan</p>
    </div>

    {{-- Password --}}
    <div class="mb-4">
        <label class="block font-semibold text-gray-700">Password</label>
        <input type="password" name="password"
               class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Confirm --}}
    <div class="mb-6">
        <label class="block font-semibold text-gray-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation"
               class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Submit --}}
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded bg-gray-500 text-white">Batal</a>
</form>

<script>
function toggleRoomSelect(){
    let role = document.getElementById("roleInput").value;
    document.getElementById("roomField").classList.toggle("hidden", role !== "guru");
}

document.addEventListener('DOMContentLoaded', toggleRoomSelect);
</script>

@endsection
