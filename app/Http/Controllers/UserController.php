<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* ==========================================================
        🧾 LIST USER
    ==========================================================*/
    public function index()
    {
        $users = User::where('role','!=','admin')->get(); // admin tidak ikut tampil
        return view('admin.users.index', compact('users'));
    }

    /* ==========================================================
        ➕ FORM TAMBAH USER
    ==========================================================*/
    public function create()
    {
        return view('admin.users.create', [
            'rooms' => Room::all(),
            'roles' => ['guru','siswa'] // tidak include admin
        ]);
    }

    /* ==========================================================
        💾 SIMPAN USER
    ==========================================================*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|confirmed|min:6',
            'role'      => 'required|in:guru,siswa',
            'room_id'   => 'nullable|exists:rooms,id'
        ]);

        // Guru boleh tanpa ruangan (guru biasa) atau ditugaskan ke satu ruangan (punya akses approve)
        $roomId = $data['role'] === 'guru' ? ($data['room_id'] ?? null) : null;

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'room_id'   => $roomId,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success','Pengguna berhasil ditambahkan');
    }

    /* ==========================================================
        ✏ EDIT USER
    ==========================================================*/
    public function edit(User $user)
    {
        if($user->role === 'admin') abort(403);

        return view('admin.users.edit', [
            'user'  => $user,
            'rooms' => Room::all(),
            'roles' => ['guru','siswa']
        ]);
    }

    /* ==========================================================
        🔄 UPDATE USER
    ==========================================================*/
    public function update(Request $request, User $user)
    {
        if($user->role === 'admin') abort(403);

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => "required|email|unique:users,email,{$user->id}",
            'password'  => 'nullable|confirmed|min:6',
            'role'      => 'required|in:guru,siswa',
            'room_id'   => 'nullable|exists:rooms,id',
        ]);

        $roomId = $data['role'] === 'guru' ? ($data['room_id'] ?? null) : null;

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'room_id'   => $roomId,
        ]);

        if($data['password'])
            $user->update(['password'=>Hash::make($data['password'])]);

        return redirect()->route('admin.users.index')
            ->with('success','Pengguna berhasil diperbarui');
    }

    /* ==========================================================
        ❌ HAPUS USER
    ==========================================================*/
    public function destroy(User $user)
    {
        if($user->role === 'admin') abort(403);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success','Pengguna berhasil dihapus');
    }
}
