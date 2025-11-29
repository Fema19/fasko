<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // ============================
    // INDEX
    // ============================
    public function index()
    {
        $rooms = Room::with(['user','facilities'])->get(); 
        return view('admin.rooms.index', compact('rooms'));
    }

    // ============================
    // SHOW DETAIL
    // ============================
    public function show(Room $room)
    {
        $room->load(['user','facilities']);
        return view('admin.rooms.show', compact('room'));
    }

    // ============================
    // CREATE
    // ============================
    public function create()
    {
        $gurus = User::where('role','guru')->get();
        return view('admin.rooms.create', compact('gurus'));
    }

    // ============================
    // STORE
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'code'    => 'nullable',
            'user_id' => 'nullable|exists:users,id'
        ]);

        Room::create($request->only('name','code','user_id'));

        return redirect()->route('admin.rooms.index')
                         ->with('success','Ruangan berhasil ditambahkan!');
    }

    // ============================
    // EDIT
    // ============================
    public function edit(Room $room)
    {
        $gurus = User::where('role','guru')->get();
        return view('admin.rooms.edit', compact('room','gurus'));
    }

    // ============================
    // UPDATE
    // ============================
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name'    => 'required',
            'code'    => 'nullable',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $room->update($request->only('name','code','user_id'));

        return redirect()->route('admin.rooms.index')
                         ->with('success','Ruangan berhasil diperbarui!');
    }

    // ============================
    // DELETE
    // ============================
    public function destroy(Room $room)
    {
        $room->facilities()->delete();
        $room->delete();

        return redirect()->route('admin.rooms.index')
                         ->with('success','Ruangan berhasil dihapus!');
    }
}
