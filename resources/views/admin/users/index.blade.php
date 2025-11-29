@extends('layouts.admin')
@section('page_title', 'Manajemen Pengguna')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Pengguna</h1>

    <a href="{{ route('admin.users.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
       + Tambah Pengguna
    </a>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-lg overflow-x-auto">

    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Nama</th>
                <th class="px-4 py-3 text-left font-semibold">Email</th>
                <th class="px-4 py-3 text-left font-semibold">Role</th>
                <th class="px-4 py-3 text-center font-semibold">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($users as $user)

            @php
                $badge = match($user->role) {
                    'admin' => 'bg-red-200 text-red-800',
                    'guru'  => 'bg-blue-200 text-blue-800',
                    default => 'bg-green-200 text-green-800'
                };
            @endphp

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                <td class="px-4 py-3">{{ $user->email }}</td>

                <td class="px-4 py-3">
                    <span class="px-3 py-1 text-xs font-bold rounded {{ $badge }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>

               

                <td class="px-4 py-3 text-center flex items-center justify-center gap-3">

                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                       Edit
                    </a>

                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-800 font-medium">
                            Hapus
                        </button>
                    </form>

                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5" class="py-6 text-center text-gray-500">
                    Belum ada data pengguna
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>

@endsection
