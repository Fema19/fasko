<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // List semua ruangan
    public function index()
    {
        $rooms = Room::with('facilities')->get();

        return $this->view('rooms.index', compact('rooms'));
    }

    // Detail ruangan + fasilitas di ruangan itu
    public function show($id)
    {
        $room = Room::with('facilities')->findOrFail($id);

        return $this->view('rooms.show', compact('room'));
    }

    // Tambah ruangan baru
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Room::create($request->only('name'));

        return back()->with('success', 'Room created.');
    }

    // Update ruangan
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate(['name' => 'required']);
        $room->update($request->only('name'));

        return back()->with('success', 'Room updated.');
    }

    // Hapus ruangan + semua fasilitas di dalamnya
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->facilities()->delete(); // hapus fasilitasnya juga
        $room->delete();

        return back()->with('success', 'Room deleted.');
    }

    /* ============================
       FASILITAS (CRUD)
       ============================ */

    // Tambah fasilitas ke ruangan
    public function addFacility(Request $request, $room_id)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
        ]);

        Facility::create([
            'room_id' => $room_id,
            'name' => $request->name,
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Facility added.');
    }

    // Update fasilitas (hanya fasilitas dalam ruangan itu)
    public function updateFacility(Request $request, $room_id, $facility_id)
    {
        $facility = Facility::where('room_id', $room_id)
            ->where('id', $facility_id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
        ]);

        $facility->update($request->only('name', 'quantity'));

        return back()->with('success', 'Facility updated.');
    }

    // Hapus fasilitas (harus memastikan fasilitas milik ruangan itu)
    public function deleteFacility($room_id, $facility_id)
    {
        $facility = Facility::where('room_id', $room_id)
            ->where('id', $facility_id)
            ->firstOrFail();

        $facility->delete();

        return back()->with('success', 'Facility deleted.');
    }
}
