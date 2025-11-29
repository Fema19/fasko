<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'room_id',
    ];

    // Ruangan utama yang dikelola guru (jika ada)
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // 1 Guru -> banyak ruangan (relasi lama jika dibutuhkan)
    public function rooms()
    {
        return $this->hasMany(Room::class, 'user_id');
    }

    // Peminjaman yang dilakukan user
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Peminjaman yang sudah diapprove oleh user(admin/guru)
    public function approvedBookings()
    {
        return $this->hasMany(Booking::class, 'approved_by');
    }

    // Laporan kerusakan yang dibuat user
    public function repairReports()
    {
        return $this->hasMany(RepairReport::class);
    }

    // Pesan yang dikirim user
    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Pesan masuk user
    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
