@extends('layouts.siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Laporan Kerusakan</h1>
        <p class="text-sm text-slate-500">Laporkan kerusakan fasilitas beserta foto (opsional).</p>
    </div>

    <div class="bg-white border rounded-lg p-5">
        @if($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 text-red-700 text-sm p-3">
                <p class="font-semibold mb-1">Periksa kembali:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('siswa.reports.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Fasilitas</label>
                <select name="facility_id" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200" required>
                    <option value="">-- Pilih Fasilitas --</option>
                    @foreach($facilities as $f)
                        <option value="{{ $f->id }}" {{ old('facility_id')==$f->id?'selected':'' }}>
                            {{ $f->name }} ({{ $f->room->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Kerusakan</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200" required>{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto (opsional)</label>
                <input type="file" name="photo" class="w-full text-sm">
            </div>

            <div class="pt-2">
                <button class="px-4 py-2 bg-slate-900 text-white rounded text-sm hover:bg-slate-800">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>
@endsection
