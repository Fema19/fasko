@extends('layouts.admin')
@section('page_title', 'Edit Pengguna')

@section('content')

<h1 class="text-3xl font-bold mb-6">Edit Pengguna</h1>

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')

    {{-- Nama --}}
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}"
               class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Email --}}
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}"
               class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror" required>
        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Role --}}
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Role</label>
        <select name="role" onchange="toggleRoomSelect()"
                class="w-full border rounded px-3 py-2 @error('role') border-red-500 @enderror" required>
            <option value="guru"  {{ old('role', $user->role) === 'guru'?'selected':'' }}>Guru</option>
            <option value="siswa" {{ old('role', $user->role) === 'siswa'?'selected':'' }}>Siswa</option>
        </select>
        @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Ruangan untuk guru --}}
    <div class="mb-4" id="room-select" style="display: {{ old('role', $user->role)=='guru'?'block':'none' }}">
        <label class="block text-gray-700 font-bold mb-2">Ruangan (Khusus Guru)</label>
        <select name="room_id" class="w-full border rounded px-3 py-2 @error('room_id') border-red-500 @enderror">
            <option value="">Pilih Ruangan</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}" {{ old('room_id',$user->room_id)==$room->id?'selected':'' }}>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
        @error('room_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Password --}}
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Password (kosongkan jika tidak ingin mengubah)</label>
        <input type="password" name="password"
               class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div class="mb-6">
        <label class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
    </div>

    <div class="flex gap-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</a>
    </div>
</form>

{{-- Script dalam section, AMAN --}}
<script>
    function toggleRoomSelect(){
        const role = document.querySelector('[name="role"]').value;
        document.getElementById('room-select').style.display = role === 'guru' ? 'block' : 'none';
    }
</script>

@endsection
