<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class CancelLateCheckins extends Command
{
    protected $signature = 'bookings:cancel-late-checkins';

    protected $description = 'Batalkan booking approved yang tidak check-in dalam 5 menit pada window 30 menit sebelum mulai';

    public function handle(): int
    {
        $now = now();
        $affected = 0;

        Booking::where('status', 'approved')
            ->where('checked_in', false)
            ->get()
            ->each(function($booking) use ($now, &$affected) {
                $windowStart = $booking->start_time->copy()->subMinutes(30)->startOfMinute();
                $windowEnd = $windowStart->copy()->addMinutes(5)->subSecond();
                if ($now->gt($windowEnd)) {
                    $booking->update(['status' => 'cancelled']);
                    $affected++;
                }
            });

        $this->info("Booking dibatalkan karena tidak check-in tepat waktu: {$affected}");
        return Command::SUCCESS;
    }
}
