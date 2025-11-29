<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    // ==========================
    // INDEX (SHOW ALL)
    // ==========================
    public function index()
    {
        $facilities = Facility::with(['category', 'room'])->latest()->get();

        return $this->view('facilities.index', compact('facilities'));
    }

    // ==========================
    // CREATE
    // ==========================
    public function create()
    {
        $categories = Category::all();
        $rooms = Room::all();

        return $this->view('facilities.create', compact('categories', 'rooms'));
    }

    // ==========================
    // STORE
    // ==========================
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak,perawatan,hilang',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('facility_photos', 'public');
        }

        Facility::create($data);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan');
    }

    // ==========================
    // EDIT
    // ==========================
    public function edit(Facility $facility)
    {
        $categories = Category::all();
        $rooms = Room::all();

        return $this->view('facilities.edit', compact('facility', 'categories', 'rooms'));
    }

    // ==========================
    // UPDATE
    // ==========================
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:baik,rusak,perawatan,hilang',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('facility_photos', 'public');
        }

        $facility->update($data);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil diperbarui');
    }

    // ==========================
    // DELETE
    // ==========================
    public function destroy(Facility $facility)
    {
        $facility->delete();

        return back()->with('success', 'Fasilitas berhasil dihapus');
    }
}
