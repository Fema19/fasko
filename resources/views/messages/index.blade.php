@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold mb-4">Daftar Pesan</h2>

@if(session('success'))
    <div class="p-3 mb-4 bg-green-500 text-white rounded">{{ session('success') }}</div>
@endif

<a href="{{ route('messages.create') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
   Buat Pesan Baru
</a>

<div class="mt-4 overflow-x-auto">
    <table class="w-full bg-white shadow rounded overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Subjek</th>
                <th class="p-3 text-left">Dibuat</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $message)
            <tr class="border-b">
                <td class="p-3">{{ $loop->iteration }}</td>
                <td class="p-3">{{ $message->name }}</td>
                <td class="p-3">{{ $message->email }}</td>
                <td class="p-3">{{ $message->subject }}</td>
                <td class="p-3">{{ $message->created_at->format('d M Y') }}</td>
                <td class="p-3 flex gap-2">

                    <a href="{{ route('messages.show', $message->id) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Detail</a>

                    @if(auth()->user()->role !== 'siswa')
                        <a href="{{ route('messages.edit', $message->id) }}"
                           class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>

                        <form action="{{ route('messages.destroy', $message->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus pesan?')"
                                class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Hapus
                            </button>
                        </form>
                    @endif

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-4 text-gray-600">Belum ada pesan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection
