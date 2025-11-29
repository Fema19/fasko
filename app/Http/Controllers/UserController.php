<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): mixed
    {
        $users = User::where('role', '!=', 'admin')->get();

        return $this->view('users.index', compact('users'));
    }

    public function create(): mixed
    {
        $rooms = Room::all();
        $roles = ['guru', 'siswa'];

        return $this->view('users.create', compact('rooms', 'roles'));
    }

    public function store(Request $request): mixed
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:guru,siswa',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        // Jika role guru, room_id harus dipilih
        if ($data['role'] === 'guru' && ! $data['room_id']) {
            return back()->withErrors(['room_id' => 'Ruangan harus dipilih untuk guru']);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'room_id' => $data['room_id'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', "User {$user->name} berhasil dibuat");
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user): mixed
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak bisa edit akun admin');
        }

        $rooms = Room::all();
        $roles = ['guru', 'siswa'];

        return $this->view('users.edit', compact('user', 'rooms', 'roles'));
    }

    public function update(Request $request, User $user): mixed
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak bisa edit akun admin');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|confirmed|min:6',
            'role' => 'required|in:guru,siswa',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        // Jika role guru, room_id harus dipilih
        if ($data['role'] === 'guru' && ! $data['room_id']) {
            return back()->withErrors(['room_id' => 'Ruangan harus dipilih untuk guru']);
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'room_id' => $data['room_id'] ?? null,
        ]);

        if ($data['password']) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return redirect()->route('admin.users.index')->with('success', "User {$user->name} berhasil diperbarui");
    }

    public function destroy(User $user): mixed
    {
        if ($user->role === 'admin') {
            abort(403, 'Tidak bisa hapus akun admin');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
