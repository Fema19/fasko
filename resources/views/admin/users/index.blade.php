@extends('layouts.admin')
@section('page_title', 'Manajemen Pengguna')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manajemen Pengguna</h1>
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Pengguna</a>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Role</th>
                <th class="px-4 py-2 text-left">Ruangan</th>
                <th class="px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">

        @foreach ($users as $user)

            @php
                $badge = match($user->role) {
                    'admin' => 'bg-red-100 text-red-700',
                    'guru'  => 'bg-blue-100 text-blue-700',
                    default => 'bg-green-100 text-green-700'
                };
            @endphp

            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $user->name }}</td>
                <td class="px-4 py-2">{{ $user->email }}</td>
                <td class="px-4 py-2">
                    <span class="px-3 py-1 rounded text-sm font-semibold {{ $badge }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-4 py-2">{{ $user->room->name ?? '-' }}</td>

                <td class="px-4 py-2 text-center space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">Edit</a>

                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </td>
            </tr>

        @endforeach

        @if ($users->isEmpty())
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">Belum ada pengguna</td>
            </tr>
        @endif

        </tbody>
    </table>
</div>
@endsection
