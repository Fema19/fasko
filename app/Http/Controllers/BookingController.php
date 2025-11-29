<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['facility', 'user'])->latest()->get();

        return $this->view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $facilities = Facility::all();

        return $this->view('bookings.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'nullable|string',
            'capacity_used' => 'required|integer|min:1',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'facility_id' => $request->facility_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'capacity_used' => $request->capacity_used,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat');
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Booking disetujui');
    }

    public function reject(Booking $booking)
    {
        $booking->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Booking ditolak');
    }

    public function checkIn(Booking $booking)
    {
        $booking->update([
            'checked_in' => true,
            'check_in_time' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Check-in berhasil');
    }
}
