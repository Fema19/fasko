<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;

trait HandlesBookings
{
    /**
     * Hitung stok tersisa pada rentang waktu booking tertentu.
     */
    protected function remainingStock(Facility $facility, Booking $booking, $excludeBookingId = null): int
    {
        $maxStock = $facility->max_availability;

        $used = Booking::where('facility_id', $facility->id)
            ->whereIn('status', ['approved','active'])
            ->when($excludeBookingId, fn($q) => $q->where('id','!=',$excludeBookingId))
            ->where(function($q) use ($booking){
                $q->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                  ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                  ->orWhere(function($q) use ($booking){
                      $q->where('start_time','<=',$booking->start_time)
                        ->where('end_time','>=',$booking->end_time);
                  });
            })
            ->sum('capacity_used');

        return $maxStock - $used;
    }

    /**
     * Batalkan booking pending lain yang bentrok di waktu yang sama ketika stok habis.
     */
    protected function cancelPendingConflicts(Facility $facility, Booking $approvedBooking): void
    {
        Booking::where('facility_id', $facility->id)
            ->where('status', 'pending')
            ->where('id','!=',$approvedBooking->id)
            ->where(function($q) use ($approvedBooking){
                $q->whereBetween('start_time', [$approvedBooking->start_time, $approvedBooking->end_time])
                  ->orWhereBetween('end_time', [$approvedBooking->start_time, $approvedBooking->end_time])
                  ->orWhere(function($q) use ($approvedBooking){
                      $q->where('start_time','<=',$approvedBooking->start_time)
                        ->where('end_time','>=',$approvedBooking->end_time);
                  });
            })
            ->update(['status' => 'cancelled']);
    }

    /**
     * Batalkan otomatis booking approved yang tidak check-in dalam 5 menit (window 30-25 menit sebelum start).
     */
    protected function cancelLateCheckins(): void
    {
        $now = now();
        Booking::where('status','approved')
            ->where('checked_in', false)
            ->get()
            ->each(function($booking) use ($now) {
                $windowStart = $booking->start_time->copy()->subMinutes(30)->startOfMinute();
                $windowEnd = $windowStart->copy()->addMinutes(5)->subSecond();
                if ($now->gt($windowEnd)) {
                    $booking->update(['status' => 'cancelled']);
                }
            });
    }

    /**
     * Ambil filter umum untuk riwayat booking.
     */
    protected function historyFilters(Request $request): array
    {
        return [
            'status' => $request->get('status'),
            'date'   => $request->get('date'),
            'q'      => $request->get('q'),
        ];
    }

    /**
     * Terapkan filter riwayat booking pada query.
     */
    protected function applyHistoryFilters($query, array $filters): void
    {
        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        if ($filters['date']) {
            $query->whereDate('start_time', $filters['date']);
        }

        if ($filters['q']) {
            $query->where(function($q) use ($filters){
                $q->whereHas('user', function($u) use ($filters){
                    $u->where('name','like','%'.$filters['q'].'%');
                })->orWhereHas('facility', function($f) use ($filters){
                    $f->where('name','like','%'.$filters['q'].'%');
                })->orWhere('reason','like','%'.$filters['q'].'%');
            });
        }
    }
}
