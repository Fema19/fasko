@extends('layouts.admin')
@section('page_title','Edit Pengguna')

@section('content')

<h1 class="text-3xl font-bold mb-6">Edit Pengguna</h1>

<form method="POST" action="{{ route('admin.users.update',$user) }}" class="bg-white p-6 rounded shadow max-w-xl">
    @csrf
    @method('PUT')

    {{-- Nama --}}
    <div class="mb-4">
        <label class="font-semibold text-gray-800">Nama</label>
        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Email --}}
    <div class="mb-4">
        <label class="font-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="w-full border rounded px-3 py-2" required>
    </div>

    {{-- Role --}}
    <div class="mb-4">
        <label class="font-semibold">Role</label>
        <select name="role" id="roleInput" onchange="toggleRoomSelect()" class="w-full border rounded px-3 py-2">
            <option value="guru"  {{ $user->role=='guru'?'selected':'' }}>Guru</option>
            <option value="siswa" {{ $user->role=='siswa'?'selected':'' }}>Siswa</option>
        </select>
    </div>

    {{-- Room (for guru) --}}
    <div class="mb-4 {{ $user->role=='guru'?'':'hidden' }}" id="roomField">
        <label class="font-semibold">Ruangan yang dikelola (opsional)</label>
        <select name="room_id" class="w-full border rounded px-3 py-2">
            <option value="">Guru tanpa ruangan</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}" {{ $user->room_id==$room->id?'selected':'' }}>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Password --}}
    <div class="mb-6">
        <label class="font-semibold">Password (opsional)</label>
        <input type="password" name="password" class="w-full border rounded px-3 py-2">
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded bg-gray-500 text-white">Batal</a>
</form>

<script>
function toggleRoomSelect(){
    let role = document.getElementById("roleInput").value;
    document.getElementById("roomField").classList.toggle("hidden", role !== "guru");
}
</script>

@endsection
