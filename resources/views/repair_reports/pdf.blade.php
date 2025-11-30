<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f3f3; font-size: 11px; }
        .small { font-size: 10px; color: #555; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; }
    </style>
</head>
<body>
    <h1>Laporan Kerusakan</h1>
    <p class="small">
        Dicetak: {{ now()->format('d M Y H:i') }} |
        Peran: {{ ucfirst($user->role) }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Fasilitas</th>
                <th>Pelapor</th>
                <th>Status</th>
                <th>Deskripsi</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $r)
            <tr>
                <td>
                    <div>{{ $r->facility->name ?? '-' }}</div>
                    <div class="small">Ruangan: {{ $r->facility->room->name ?? '-' }}</div>
                </td>
                <td>
                    <div>{{ $r->user->name ?? '-' }}</div>
                    <div class="small">{{ $r->user->email ?? '-' }}</div>
                </td>
                <td>{{ $r->status ?? 'pending' }}</td>
                <td>{{ $r->description }}</td>
                <td>
                    <div>{{ optional($r->created_at)->format('d M Y H:i') }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada laporan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
