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
    </style>
</head>
<body>
    <h1>History Booking</h1>
    <p class="small">
        Dicetak: {{ now()->format('d M Y H:i') }} |
        Peran: {{ ucfirst($user->role) }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Fasilitas</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $b)
            <tr>
                <td>
                    <div>{{ $b->user->name }}</div>
                    <div class="small">{{ $b->user->email }}</div>
                </td>
                <td>
                    <div>{{ $b->facility->name }}</div>
                    <div class="small">Ruangan: {{ $b->facility->room->name ?? '-' }}</div>
                    <div class="small">Unit dipakai: {{ $b->capacity_used }}</div>
                </td>
                <td>
                    <div>{{ optional($b->start_time)->format('d M Y H:i') }}</div>
                    <div class="small">s/d {{ optional($b->end_time)->format('d M Y H:i') }}</div>
                </td>
                <td>
                    <div>{{ $b->status }}</div>
                    @if($b->checked_out && $b->check_out_time)
                        <div class="small">Checkout: {{ $b->check_out_time->format('d M Y H:i') }}</div>
                    @elseif($b->checked_in && $b->check_in_time)
                        <div class="small">Check-in: {{ $b->check_in_time->format('d M Y H:i') }}</div>
                    @endif
                </td>
                <td>{{ $b->reason ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada data untuk filter ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
