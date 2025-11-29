@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Laporan Kerusakan</h1>
        <a href="{{ route('siswa.report.create') }}" class="px-3 py-2 bg-green-600 text-white rounded">Buat Laporan</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Fasilitas</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reports as $r)
                    <tr>
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2">{{ $r->facility->name }}</td>
                        <td class="px-4 py-2">{{ $r->status }}</td>
                        <td class="px-4 py-2 text-xs">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-4 text-center">Belum ada laporan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
