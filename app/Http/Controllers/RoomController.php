<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // ============================
    // INDEX
    // ============================
    public function index()
    {
        $rooms = Room::with('facilities')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    // ============================
    // SHOW DETAIL
    // ============================
    public function show(Room $room)
    {
        $room->load('facilities');
        return view('admin.rooms.show', compact('room'));
    }

    // ============================
    // CREATE
    // ============================
    public function create()
    {
        return view('admin.rooms.create');
    }

    // ============================
    // STORE
    // ============================
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Room::create($request->only('name', 'code'));

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan!');
    }

    // ============================
    // EDIT
    // ============================
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    // ============================
    // UPDATE
    // ============================
    public function update(Request $request, Room $room)
    {
        $request->validate(['name' => 'required']);
        $room->update($request->only('name', 'code'));

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui!');
    }

    // ============================
    // DELETE RUANGAN + FASILITAS
    // ============================
    public function destroy(Room $room)
    {
        $room->facilities()->delete();
        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }

    // ============================
    // FASILITAS DALAM RUANGAN
    // ============================
    public function addFacility(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
        ]);

        $room->facilities()->create($request->only('name', 'quantity'));

        return back()->with('success', 'Fasilitas ditambahkan');
    }

    public function updateFacility(Request $request, Room $room, Facility $facility)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
        ]);

        $facility->update($request->only('name', 'quantity'));

        return back()->with('success', 'Fasilitas diperbarui');
    }

    public function deleteFacility(Room $room, Facility $facility)
    {
        $facility->delete();
        return back()->with('success', 'Fasilitas dihapus');
    }
}
